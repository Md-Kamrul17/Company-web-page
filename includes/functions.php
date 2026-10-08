<?php
// ── ডাবল লোড থেকে সুরক্ষা ────────────────────────────
if (defined('MAF_FUNCTIONS_LOADED')) return;
define('MAF_FUNCTIONS_LOADED', true);

// ── Database config লোড করুন ──────────────────────────
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// ── Output helpers ───────────────────────────────────
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function clean(string $s): string { return trim(strip_tags($s)); }
function slug(string $s): string {
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9\s-]/', '', $s);
    return trim(preg_replace('/[\s-]+/', '-', $s), '-');
}

// ── CSRF ────────────────────────────────────────────
function csrfToken(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verifyCsrf(string $t): bool {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t);
}
function csrfField(): string { return '<input type="hidden" name="csrf_token" value="'.csrfToken().'">'; }

// ── Settings ─────────────────────────────────────────
function setting(string $key, string $def = ''): string {
    static $c = [];
    if (!isset($c[$key])) {
        $r = DB::fetchOne("SELECT setting_value FROM site_settings WHERE setting_key=?", [$key]);
        $c[$key] = $r ? $r['setting_value'] : $def;
    }
    return $c[$key];
}
function allSettings(): array {
    $rows = DB::fetchAll("SELECT setting_key,setting_value FROM site_settings");
    return array_column($rows, 'setting_value', 'setting_key');
}

// ── Page meta ─────────────────────────────────────────
function pageMeta(string $slug): array {
    return DB::fetchOne("SELECT * FROM page_meta WHERE page_slug=?", [$slug])
        ?? ['meta_title'=>setting('site_name'),'meta_description'=>setting('site_tagline'),'og_image'=>''];
}

// ── Media helpers ─────────────────────────────────────
function mediaUrl(?string $path): string {
   /*--<!-- if (!$path) return SITE_URL.'/public/images/placeholder.png'; ----*/
   if (!$path) return SITE_URL.'/public/images/placeholder.svg'; 
    if (str_starts_with($path,'http')) return $path;
    return UPLOAD_URL . $path;
}
function uploadFile(array $file, string $dir, array $allowed=['jpg','jpeg','png','webp','gif']): ?string {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    if ($file['size'] > 8*1024*1024) return null;
    $dest = UPLOAD_DIR . $dir . '/';
    if (!is_dir($dest)) mkdir($dest, 0755, true);
    $name = time().'_'.bin2hex(random_bytes(5)).'.'.$ext;
    return move_uploaded_file($file['tmp_name'], $dest.$name) ? $dir.'/'.$name : null;
}
function uploadDoc(array $file, string $dir='docs'): ?string {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext,['pdf','doc','docx'])) return null;
    if ($file['size'] > 20*1024*1024) return null;
    $dest = UPLOAD_DIR . $dir . '/';
    if (!is_dir($dest)) mkdir($dest, 0755, true);
    $name = time().'_'.bin2hex(random_bytes(5)).'.'.$ext;
    return move_uploaded_file($file['tmp_name'], $dest.$name) ? $dir.'/'.$name : null;
}

// ── Flash messages ────────────────────────────────────
function flash(string $type, string $msg): void { $_SESSION['flash'] = compact('type','msg'); }
function getFlash(): ?array {
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f;
}
function flashHtml(): string {
    $f = getFlash(); if (!$f) return '';
    $icons = ['success'=>'✓','error'=>'✕','info'=>'ℹ'];
    $icon  = $icons[$f['type']] ?? 'ℹ';
    return '<div class="alert alert-'.$f['type'].'">'.$icon.' '.e($f['msg']).'</div>';
}

// ── Redirect ──────────────────────────────────────────
function redirect(string $url): never { header('Location: '.$url); exit; }

// ── Dates ─────────────────────────────────────────────
function fmtDate(string $d, string $fmt='F j, Y'): string { return date($fmt, strtotime($d)); }
function timeAgo(string $d): string {
    $t = time() - strtotime($d);
    if ($t<60)     return 'just now';
    if ($t<3600)   return floor($t/60).'m ago';
    if ($t<86400)  return floor($t/3600).'h ago';
    if ($t<604800) return floor($t/86400).'d ago';
    return fmtDate($d,'M j, Y');
}

// ── Auth ──────────────────────────────────────────────
function adminLoggedIn(): bool { return !empty($_SESSION[ADMIN_SESSION]['id']); }
function requireAdmin(): void {
    if (!adminLoggedIn()) redirect(SITE_URL.'/admin/login.php');
}
function currentAdmin(): ?array { return $_SESSION[ADMIN_SESSION] ?? null; }

// ── Pagination ────────────────────────────────────────
function paginate(string $countSql, int $page, int $perPage, array $params=[]): array {
    $total = (int)(DB::fetchOne($countSql, $params)['c'] ?? 0);
    $pages = max(1,(int)ceil($total/$perPage));
    $page  = max(1,min($page,$pages));
    return ['total'=>$total,'pages'=>$pages,'current'=>$page,'offset'=>($page-1)*$perPage,'per_page'=>$perPage];
}
