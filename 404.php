<?php
define('PAGE_SLUG','home');
require_once __DIR__.'/includes/functions.php';
http_response_code(404);
require_once __DIR__.'/includes/header.php';
?>
<section class="page-hero" style="min-height:60vh;display:flex;align-items:center">
  <div class="container page-hero-content" style="text-align:center;width:100%">
    <div style="font-family:var(--font-h);font-size:120px;font-weight:900;color:var(--red);line-height:1;margin-bottom:16px">404</div>
    <h1 class="sec-title light" style="margin-bottom:16px">Page Not Found</h1>
    <p style="font-size:15px;color:rgba(255,255,255,.5);margin-bottom:36px">The page you're looking for doesn't exist or has been moved.</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      <a href="<?=SITE_URL?>" class="btn btn-red">← Go Home</a>
      <a href="<?=SITE_URL?>/pages/contact.php" class="btn btn-outline">Contact Us</a>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/includes/footer.php'; ?>
