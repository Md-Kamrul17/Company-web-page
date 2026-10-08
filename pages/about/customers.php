<?php
define('PAGE_SLUG','customers');
require_once __DIR__.'/../../includes/functions.php';
$customers = DB::fetchAll("SELECT * FROM customers WHERE is_active=1 ORDER BY sort_order");
$markets   = DB::fetchAll("SELECT * FROM export_markets WHERE is_active=1 ORDER BY sort_order");
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="#">About Us</a><span>/</span>Customer</div>
    <div class="eyebrow">Our Clients</div>
    <h1 class="sec-title" style="color:#fff">Our Customers</h1>
    <p class="page-hero-desc">Trusted by global brands across France, Spain, Brazil, Japan and other EU countries.</p>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <?php if($customers): ?>
    <div class="fu" style="margin-bottom:48px">
      <div class="eyebrow">Brand Partners</div>
      <h2 class="sec-title" style="font-size:32px;margin-bottom:32px">Our Global Clients</h2>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px">
        <?php foreach($customers as $c): ?>
        <div style="background:var(--gray-bg);padding:36px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(0,0,0,.07);transition:.2s" onmouseover="this.style.borderColor='var(--red)'" onmouseout="this.style.borderColor='rgba(0,0,0,.07)'">
          <?php if($c['logo']): ?>
          <img src="<?=mediaUrl($c['logo'])?>" alt="<?=e($c['name'])?>" style="max-height:60px;object-fit:contain"/>
          <?php else: ?>
          <div style="font-family:var(--font-h);font-size:14px;font-weight:800;text-transform:uppercase;color:var(--dark);text-align:center"><?=e($c['name'])?><?php if($c['country']): ?><br/><span style="font-size:10px;color:var(--gray)"><?=e($c['country'])?></span><?php endif; ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Export Markets -->
    <div class="fu" style="background:var(--dark);padding:56px;border-top:4px solid var(--red)">
      <div class="eyebrow" style="color:var(--red)">Export Destinations</div>
      <h2 class="sec-title light" style="font-size:30px;margin-bottom:32px">Where We Export</h2>
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:20px">
        <?php foreach($markets as $m): ?>
        <div style="text-align:center;padding:20px">
          <div style="font-size:36px;margin-bottom:8px"><?=e($m['flag_emoji'])?></div>
          <div style="font-family:var(--font-h);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.5)"><?=e($m['country'])?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if(!$customers): ?>
    <div style="text-align:center;padding:80px;background:var(--gray-bg)">
      <p style="font-family:var(--font-h);font-size:14px;color:var(--gray)">Customer information coming soon.</p>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
