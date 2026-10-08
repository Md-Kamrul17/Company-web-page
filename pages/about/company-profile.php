<?php /* company-profile.php */
define('PAGE_SLUG','about-profile');
require_once __DIR__.'/../../includes/functions.php';
$page = DB::fetchOne("SELECT * FROM company_pages WHERE page_slug='company-profile'");
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="#">About Us</a><span>/</span>Company Profile</div>
    <div class="eyebrow">Corporate</div>
    <h1 class="sec-title" style="color:#fff">Company Profile</h1>
  </div>
</section>
<section class="content-section">
  <div class="container">
    <div style="max-width:900px;margin:0 auto">
      <div class="fu">
        <div class="eyebrow">Registration &amp; Details</div>
        <h2 class="sec-title" style="font-size:32px;margin-bottom:32px">Company Snapshot</h2>
        <div class="content-body"><?=$page['content']??''?></div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
