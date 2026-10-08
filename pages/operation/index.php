<?php /* operation/index.php */
define('PAGE_SLUG','operation');
require_once __DIR__.'/../../includes/functions.php';
$units = DB::fetchAll("SELECT * FROM operation_strength WHERE is_active=1 ORDER BY sort_order");
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span>Operation Strength</div>
    <div class="eyebrow">Our Capabilities</div>
    <h1 class="sec-title" style="color:#fff">Operation Strength</h1>
    <p class="page-hero-desc">Well equipped and well experienced teams providing instant technical and production support from scratch to finished product.</p>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="fu" style="text-align:center;max-width:680px;margin:0 auto 56px">
      <div class="eyebrow" style="justify-content:center">What Makes Us Strong</div>
      <h2 class="sec-title">Six Pillars of<br/>Operational Excellence</h2>
    </div>
    <div class="unit-grid">
      <?php foreach($units as $u): ?>
      <a href="<?=SITE_URL?>/pages/operation/detail.php?slug=<?=e($u['slug'])?>" class="unit-card fu">
        <div class="unit-card-img">
          <?php if($u['image']): ?>
          <img src="<?=mediaUrl($u['image'])?>" alt="<?=e($u['title'])?>" loading="lazy"/>
          <?php else: ?>
          <div class="unit-card-placeholder">
            <?php
            $icons=['people'=>'👥','product-development-center'=>'🔬','quality-control'=>'✅','internal-lab'=>'🧪','compliance'=>'📋','training-center'=>'🎓'];
            ?>
            <span style="font-size:48px"><?=$icons[$u['slug']]??'⚙️'?></span>
          </div>
          <?php endif; ?>
        </div>
        <div class="unit-card-body">
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
