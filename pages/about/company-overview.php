<?php
define('PAGE_SLUG','about-overview');
require_once __DIR__.'/../../includes/functions.php';
$page = DB::fetchOne("SELECT * FROM company_pages WHERE page_slug='company-overview'");
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="#">About Us</a><span>/</span>Company Overview</div>
    <div class="eyebrow">About MAF Footwear</div>
    <h1 class="sec-title" style="color:#fff"><?=e($page['page_title']??'Company Overview')?></h1>
    <p class="page-hero-desc">100% export oriented quality shoe manufacturing in Chittagong, Bangladesh.</p>
  </div>
</section>

<section class="content-section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:72px;align-items:start">
      <div class="fu">
        <div class="eyebrow">Who We Are</div>
        <h2 class="sec-title" style="font-size:32px;margin-bottom:28px">Leader in Shoe Export</h2>
        <div class="content-body"><?=$page['content']??''?></div>
      </div>
      <div class="fu">
        <!-- Export markets quick panel -->
        <div style="background:var(--dark);padding:40px;border-top:4px solid var(--red);margin-bottom:24px">
          <div class="eyebrow" style="margin-bottom:16px">Export Markets</div>
          <?php $markets=DB::fetchAll("SELECT * FROM export_markets WHERE is_active=1 ORDER BY sort_order LIMIT 8"); ?>
          <div style="display:flex;flex-wrap:wrap;gap:10px">
            <?php foreach($markets as $m): ?>
            <span style="background:rgba(255,255,255,.07);color:rgba(255,255,255,.6);font-family:var(--font-h);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;padding:6px 14px;display:inline-flex;align-items:center;gap:6px">
              <?=e($m['flag_emoji'])?> <?=e($m['country'])?>
            </span>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- Quick stats -->
        <?php $stats=DB::fetchAll("SELECT * FROM home_stats WHERE is_active=1 ORDER BY sort_order"); ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:rgba(0,0,0,.08)">
          <?php foreach($stats as $s): ?>
          <div style="background:var(--white);padding:28px 24px;border-top:3px solid var(--red)">
            <div style="font-family:var(--font-h);font-size:36px;font-weight:900;color:var(--dark);line-height:1;margin-bottom:6px">
              <?=e($s['value'])?><?=e($s['suffix'])?>
            </div>
            <div style="font-family:var(--font-h);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--gray)"><?=e($s['label'])?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
