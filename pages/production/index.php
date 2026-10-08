<?php
define('PAGE_SLUG','production');
require_once __DIR__.'/../../includes/functions.php';
$units = DB::fetchAll("SELECT * FROM production_units WHERE is_active=1 ORDER BY sort_order");
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span>Production Units</div>
    <div class="eyebrow">Vertical Integration</div>
    <h1 class="sec-title" style="color:#fff">Production Units</h1>
    <p class="page-hero-desc">Only shoe factory who produces various footwear related finish products and semi-finished products in same factory premises.</p>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <div class="fu" style="text-align:center;max-width:700px;margin:0 auto 56px">
      <div class="eyebrow" style="justify-content:center">7 Units Under One Roof</div>
      <h2 class="sec-title">Our Integrated<br/>Production Capability</h2>
      <p class="sec-desc" style="margin:14px auto 0;text-align:center">From raw EVA foam to finished athletic shoes — every critical production step happens right here in Chittagong.</p>
    </div>
    <div class="unit-grid">
      <?php foreach($units as $u): ?>
      <a href="<?=SITE_URL?>/pages/production/detail.php?slug=<?=e($u['slug'])?>" class="unit-card fu">
        <div class="unit-card-img">
          <?php if($u['image']): ?>
          <img src="<?=mediaUrl($u['image'])?>" alt="<?=e($u['title'])?>" loading="lazy"/>
          <?php else: ?>
          <div class="unit-card-placeholder">
            <svg width="48" height="30" viewBox="0 0 90 58" fill="none"><path d="M4 48 C20 48,30 12,48 12 C58 12,64 28,86 20" stroke="#CC1E1E" stroke-width="4" stroke-linecap="round" fill="none"/></svg>
          </div>
          <?php endif; ?>
        </div>
        <div class="unit-card-body">
          <div style="font-family:var(--font-h);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--red);margin-bottom:8px">Unit <?=str_pad($u['sort_order'],2,'0',STR_PAD_LEFT)?></div>
          <div class="unit-card-title"><?=e($u['title'])?></div>
          <div class="unit-card-desc"><?=e(substr($u['short_description']??'',0,110))?>…</div>
          <div class="unit-card-link">Read More <span>→</span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
