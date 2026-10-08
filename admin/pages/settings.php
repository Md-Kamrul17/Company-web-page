<?php
$pageTitle = 'Site Settings';
require_once __DIR__ . '/../../includes/functions.php';
/* ------  require_once __DIR__ . '/../includes/header.php';   -------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) die('Security error.');

    // ── Text settings ──────────────────────────────────────
    $keys = [
        'site_name','site_tagline','footer_tagline',
        'site_email','site_phone1','site_phone2',
        'site_phone3','site_phone4','site_fax',
        'site_address','site_webmail','maps_embed',
        'facebook_url','youtube_url','linkedin_url',
        'established_year','reg_number','reg_address'
    ];
    foreach ($keys as $k) {
        $v = clean($_POST[$k] ?? '');
        DB::execute(
            "INSERT INTO site_settings (setting_key,setting_value) VALUES(?,?)
             ON DUPLICATE KEY UPDATE setting_value=?",
            [$k, $v, $v]
        );
    }

    // ── Logo upload ────────────────────────────────────────
    if (!empty($_FILES['site_logo']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION));
        $allowed = ['png','jpg','jpeg','webp','svg','gif'];
        if (in_array($ext, $allowed) && $_FILES['site_logo']['size'] < 2*1024*1024) {
            // Save as maf-logo.ext in public/images/
            $imgDir  = __DIR__ . '/../../public/images/';
            if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);
            // Remove old logo files
            foreach (glob($imgDir . 'maf-logo.*') as $old) @unlink($old);
            $newName = 'maf-logo.' . $ext;
            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $imgDir . $newName)) {
                DB::execute(
                    "INSERT INTO site_settings (setting_key,setting_value) VALUES('site_logo',?)
                     ON DUPLICATE KEY UPDATE setting_value=?",
                    [$newName, $newName]
                );
                flash('success', 'Settings ও Logo সফলভাবে save হয়েছে!');
            } else {
                flash('error', 'Logo upload হয়নি। folder permission চেক করুন।');
            }
        } else {
            flash('error', 'Logo: PNG/JPG/SVG/WebP, সর্বোচ্চ 2MB।');
        }
    } else {
        flash('success', 'Settings সফলভাবে save হয়েছে!');
    }

    redirect(SITE_URL . '/admin/pages/settings.php');
}

$s        = allSettings();
$logoFile = $s['site_logo'] ?? null;
$logoUrl  = $logoFile ? SITE_URL . '/public/images/' . $logoFile : null;

/* =====================  LOAD HTML HEADER ===================== */

require_once __DIR__ . '/../includes/header.php';

?>

<?= flashHtml() ?>
<div class="pg-header">
  <div class="pg-title">Site Settings</div>
</div>

<form method="POST" enctype="multipart/form-data">
  <?= csrfField() ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    <!-- LEFT COLUMN -->
    <div>

      <!-- LOGO UPLOAD - সবার আগে -->
      <div class="panel" style="margin-bottom:20px;border-top:4px solid var(--red)">
        <div class="panel-head">
          <span class="panel-title">🖼️ Company Logo</span>
        </div>
        <div class="panel-body">
          <!-- Current logo preview -->
          <?php if ($logoUrl): ?>
          <div style="margin-bottom:16px;padding:16px;background:#f9f9f9;border:1px solid var(--border);text-align:center">
            <img src="<?= e($logoUrl) ?>?v=<?= time() ?>"
                 alt="Current Logo"
                 style="max-height:80px;max-width:100%;object-fit:contain";  />  /*===edit- stroke="#CC1E1E"; fill="#CC1E1E"=== */
            <div style="font-size:11px;color:var(--muted);margin-top:8px">বর্তমান Logo</div>
          </div>
          <?php else: ?>
          <div style="margin-bottom:16px;padding:20px;background:#f9f9f9;border:2px dashed var(--border);text-align:center">
            <!-- SVG fallback logo -->
            <svg viewBox="0 0 200 60" width="160" height="50" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 44 C22 44,32 12,50 12 C62 12,70 26,88 20" stroke="#CC1E1E" stroke-width="4.5" stroke-linecap="round" fill="none"/>
              <text x="10" y="56" font-family="Georgia,serif" font-size="14" font-weight="bold" fill="#CC1E1E">maf</text>
              <text x="34" y="56" font-family="Arial,sans-serif" font-size="13" font-weight="bold" fill="#1C1C1C"> Footwear Limited</text>
            </svg>
            <div style="font-size:11px;color:var(--muted);margin-top:8px">Default SVG Logo (কোনো ছবি নেই)</div>
          </div>
          <?php endif; ?>

          <div class="adm-fg" style="margin-bottom:0">
            <label class="adm-fl">নতুন Logo Upload করুন</label>
            <input class="adm-fi" type="file" name="site_logo" accept="image/png,image/jpeg,image/webp,image/svg+xml,image/gif"/>
            <p class="adm-hint">
              ✅ PNG (transparent background সবচেয়ে ভালো)<br/>
              ✅ JPG, WebP, SVG, GIF — সব চলবে<br/>
              ⚠️ সর্বোচ্চ 2MB | Recommended: 300×80px
            </p>
          </div>
        </div>
      </div>

      <!-- General -->
      <div class="panel" style="margin-bottom:20px">
        <div class="panel-head"><span class="panel-title">General</span></div>
        <div class="panel-body">
          <div class="adm-fg">
            <label class="adm-fl">Company Name</label>
            <input class="adm-fi" name="site_name" value="<?= e($s['site_name'] ?? 'MAF Footwear Limited') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Tagline</label>
            <input class="adm-fi" name="site_tagline" value="<?= e($s['site_tagline'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Footer Tagline</label>
            <textarea class="adm-ft" name="footer_tagline" rows="3"><?= e($s['footer_tagline'] ?? '') ?></textarea>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Established Year</label>
            <input class="adm-fi" name="established_year" value="<?= e($s['established_year'] ?? '2007') ?>"/>
          </div>
        </div>
      </div>

      <!-- Social Media -->
      <div class="panel">
        <div class="panel-head"><span class="panel-title">Social Media</span></div>
        <div class="panel-body">
          <?php foreach ([
            'facebook_url' => 'Facebook URL',
            'youtube_url'  => 'YouTube URL',
            'linkedin_url' => 'LinkedIn URL',
          ] as $k => $l): ?>
          <div class="adm-fg">
            <label class="adm-fl"><?= $l ?></label>
            <input class="adm-fi" type="url" name="<?= $k ?>" value="<?= e($s[$k] ?? '') ?>" placeholder="https://..."/>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div><!-- /LEFT -->

    <!-- RIGHT COLUMN -->
    <div>

      <!-- Contact Info -->
      <div class="panel" style="margin-bottom:20px">
        <div class="panel-head"><span class="panel-title">Contact Information</span></div>
        <div class="panel-body">
          <div class="adm-fg">
            <label class="adm-fl">Email</label>
            <input class="adm-fi" type="email" name="site_email" value="<?= e($s['site_email'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Phone 1</label>
            <input class="adm-fi" name="site_phone1" value="<?= e($s['site_phone1'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Phone 2</label>
            <input class="adm-fi" name="site_phone2" value="<?= e($s['site_phone2'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Phone 3</label>
            <input class="adm-fi" name="site_phone3" value="<?= e($s['site_phone3'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Phone 4</label>
            <input class="adm-fi" name="site_phone4" value="<?= e($s['site_phone4'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Fax</label>
            <input class="adm-fi" name="site_fax" value="<?= e($s['site_fax'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Factory Address</label>
            <textarea class="adm-ft" name="site_address" rows="3"><?= e($s['site_address'] ?? '') ?></textarea>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Website / Web Mail URL</label>
            <input class="adm-fi" name="site_webmail" value="<?= e($s['site_webmail'] ?? '') ?>"/>
          </div>
        </div>
      </div>

      <!-- Registration -->
      <div class="panel" style="margin-bottom:20px">
        <div class="panel-head"><span class="panel-title">Registration</span></div>
        <div class="panel-body">
          <div class="adm-fg">
            <label class="adm-fl">Registration Number</label>
            <input class="adm-fi" name="reg_number" value="<?= e($s['reg_number'] ?? '') ?>"/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Registered Address</label>
            <textarea class="adm-ft" name="reg_address" rows="2"><?= e($s['reg_address'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <!-- Google Maps -->
      <div class="panel" style="margin-bottom:20px">
        <div class="panel-head"><span class="panel-title">Google Maps</span></div>
        <div class="panel-body">
          <div class="adm-fg" style="margin-bottom:0">
            <label class="adm-fl">Maps Embed URL</label>
            <textarea class="adm-ft" name="maps_embed" rows="3"><?= e($s['maps_embed'] ?? '') ?></textarea>
            <p class="adm-hint">Google Maps → Share → Embed a map → src="..." থেকে URL নিন।</p>
          </div>
        </div>
      </div>

      <!-- Save button -->
      <div class="panel">
        <div class="panel-body">
          <p style="font-size:13px;color:var(--muted);margin-bottom:16px">
            সব পরিবর্তন সাথে সাথে website এ দেখা যাবে।
          </p>
          <button type="submit" class="adm-btn adm-btn-red" style="width:100%;padding:14px;font-size:13px">
            💾 Save All Settings
          </button>
          <a href="<?= SITE_URL ?>/" target="_blank"
             style="display:block;text-align:center;margin-top:12px;font-size:12px;color:var(--red)">
            🌐 Website দেখুন →
          </a>
        </div>
      </div>

    </div><!-- /RIGHT -->
  </div>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
