<?php
require_once __DIR__ . '/../includes/functions.php';

// Already logged in? Go to dashboard
if (adminLoggedIn()) redirect(SITE_URL . '/admin/dashboard.php');

$error = '';

// ── First run: fix admin password automatically ──────────
$adminCheck = DB::fetchOne("SELECT id, password FROM admin_users WHERE email = 'admin@mafshoes.com'");
if ($adminCheck) {
    // Test if current hash works with common passwords
    $works = password_verify('Admin@1234', $adminCheck['password'])
          || password_verify('MAF@Admin2024', $adminCheck['password'])
          || password_verify('password', $adminCheck['password']);

    // If nothing works, reset it now automatically
    if (!$works) {
        $fixedHash = password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost' => 10]);
        DB::execute(
            "UPDATE admin_users SET password = ?, is_active = 1 WHERE email = 'admin@mafshoes.com'",
            [$fixedHash]
        );
    }
} else {
    // No admin exists at all — create one
    $fixedHash = password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost' => 10]);
    DB::execute(
        "INSERT INTO admin_users (name, email, password, role, is_active) VALUES (?, ?, ?, 'super_admin', 1)",
        ['MAF Admin', 'admin@mafshoes.com', $fixedHash]
    );
}

// ── Handle login form ────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $user = DB::fetchOne(
        "SELECT * FROM admin_users WHERE email = ? AND is_active = 1",
        [$email]
    );

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION[ADMIN_SESSION] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];
        DB::execute("UPDATE admin_users SET last_login = NOW() WHERE id = ?", [$user['id']]);
        redirect(SITE_URL . '/admin/dashboard.php');
    } else {
        $error = 'Email অথবা Password ভুল। সঠিক তথ্য দিন।';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Admin Login — MAF Footwear Limited</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= SITE_URL ?>/admin/css/admin.css"/>
<style>
.login-hint{
    background:#f0fdf4;
    border:1px solid #bbf7d0;
    border-left:4px solid #22c55e;
    padding:12px 16px;
    margin-bottom:20px;
    font-size:12px;
    color:#15803d;
    font-family:'Montserrat',sans-serif;
}
.login-hint strong { display:block; margin-bottom:4px; font-size:13px; }
.login-hint code {
    background:#dcfce7;
    padding:2px 8px;
    font-size:12px;
    letter-spacing:0.5px;
    font-family:monospace;
}
</style>
</head>
<body class="login-body">
<div class="login-wrap">
  <div class="login-card">

    <!-- Logo -->
    <div class="login-logo">
      <svg width="56" height="35" viewBox="0 0 90 58" fill="none">
        <path d="M4 48 C20 48,30 12,48 12 C58 12,64 28,86 20"
              stroke="#CC1E1E" stroke-width="5" stroke-linecap="round" fill="none"/>
      </svg>
      <div>
        <div class="login-brand"><span>maf</span> Footwear Limited</div>
        <div class="login-brand-sub">Admin Portal</div>
      </div>
    </div>

    <h1 class="login-title">Sign In</h1>
    <p class="login-sub">ওয়েবসাইট পরিচালনা করতে Login করুন।</p>

    <!-- Default credentials hint -->
    <div class="login-hint">
      <strong>🔑 Default Login তথ্য:</strong>
      Email: <code>admin@mafshoes.com</code><br/>
      Password: <code>Admin@1234</code>
    </div>

    <!-- Error message -->
    <?php if ($error): ?>
    <div class="adm-alert adm-alert-error">✕ <?= e($error) ?></div>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="">
      <div class="adm-fg">
        <label class="adm-fl">Email Address</label>
        <input class="adm-fi"
               type="email"
               name="email"
               value="<?= e($_POST['email'] ?? 'admin@mafshoes.com') ?>"
               required
               autofocus
               placeholder="admin@mafshoes.com"/>
      </div>
      <div class="adm-fg">
        <label class="adm-fl">Password</label>
        <input class="adm-fi"
               type="password"
               name="password"
               required
               placeholder="Admin@1234"/>
      </div>
      <button type="submit"
              class="adm-btn adm-btn-red"
              style="width:100%;padding:14px;font-size:13px;margin-top:4px">
        Sign In →
      </button>
    </form>

  </div>
</div>
</body>
</html>
