<?php
define('PAGE_SLUG','products');
require_once __DIR__.'/../includes/functions.php';
$cats     = DB::fetchAll("SELECT * FROM product_categories ORDER BY sort_order");
$products = DB::fetchAll("SELECT p.*,c.slug cat_slug,c.name cat_name FROM products p LEFT JOIN product_categories c ON p.category_id=c.id WHERE p.is_active=1 ORDER BY p.sort_order,p.id");
require_once __DIR__.'/../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span>Products</div>
    <div class="eyebrow">Product Catalog</div>
    <h1 class="sec-title" style="color:#fff">Our Products</h1>
    <p class="page-hero-desc">Athletic Shoes · Sports Shoes · Casual Shoes · Direct Injection Shoes · Flip-Flop · Slippers · Rubber Sole · IMEVA Sole · PU Sole · EVA Foam</p>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <!-- Category Filters -->
    <div class="product-filters fu">
      <button class="pf-btn active" data-filter="all" data-filter-group="products">All Products</button>
      <?php foreach($cats as $c): if($c['slug']==='all') continue; ?>
      <button class="pf-btn" data-filter="<?=e($c['slug'])?>" data-filter-group="products"><?=e($c['name'])?></button>
      <?php endforeach; ?>
    </div>

    <?php if($products): ?>
    <div class="product-grid">
      <?php foreach($products as $p): ?>
      <div class="product-card fu" data-cat="<?=e($p['cat_slug']??'all')?>" data-filter-parent="products"
           data-lightbox="<?=mediaUrl($p['image'])?>" style="cursor:pointer">
        <div class="product-img">
          <img src="<?=mediaUrl($p['image'])?>" alt="<?=e($p['title']??$p['cat_name']??'Product')?>" loading="lazy"
               onerror="this.src='https://placehold.co/400x300/F5F5F5/888?text=Product'"/>
        </div>
        <?php if($p['cat_name']): ?>
        <span class="product-cat"><?=e($p['cat_name'])?></span>
        <?php endif; ?>
        <?php if($p['title']): ?>
        <div style="padding:0 16px 16px;font-family:var(--font-h);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--dark)"><?=e($p['title'])?></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:80px;background:var(--gray-bg)">
      <p style="font-family:var(--font-h);font-size:14px;color:var(--gray)">Product images will be added soon.</p>
      <a href="<?=SITE_URL?>/pages/contact.php" class="btn btn-red" style="margin-top:20px;display:inline-block">Request Catalog</a>
    </div>
    <?php endif; ?>

    <!-- Request catalog CTA -->
    <div class="fu" style="margin-top:64px;background:var(--dark);padding:48px;text-align:center;border-top:4px solid var(--red)">
      <div class="eyebrow" style="justify-content:center">Custom Orders</div>
      <h2 class="sec-title light" style="font-size:28px;margin:12px 0 16px">Looking for Something Specific?</h2>
      <p style="font-size:15px;color:rgba(255,255,255,.5);margin-bottom:28px">Contact us with your requirements — our product development team is ready to help.</p>
      <a href="<?=SITE_URL?>/pages/contact.php" class="btn btn-red">Request a Quote →</a>
    </div>
  </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
  <span class="lightbox-close">&times;</span>
  <img class="lightbox-img" id="lightboxImg" src="" alt="Product"/>
</div>

<?php require_once __DIR__.'/../includes/footer.php'; ?>
