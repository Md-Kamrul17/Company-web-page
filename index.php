<?php
define('PAGE_SLUG','home');
require_once __DIR__.'/includes/functions.php';

// ── Fetch all homepage data ───────────────────────
$slides    = DB::fetchAll("SELECT * FROM hero_slides WHERE is_active=1 ORDER BY sort_order");
$stats     = DB::fetchAll("SELECT * FROM home_stats WHERE is_active=1 ORDER BY sort_order");
$intro     = DB::fetchOne("SELECT * FROM home_intro LIMIT 1");
$qlCards   = DB::fetchAll("SELECT * FROM home_quick_links WHERE is_active=1 ORDER BY sort_order");
$markets   = DB::fetchAll("SELECT * FROM export_markets WHERE is_active=1 ORDER BY sort_order");
$ticker    = DB::fetchAll("SELECT * FROM ticker_items WHERE is_active=1 ORDER BY sort_order");
$prodUnits = DB::fetchAll("SELECT * FROM production_units WHERE is_active=1 ORDER BY sort_order");
$opsUnits  = DB::fetchAll("SELECT * FROM operation_strength WHERE is_active=1 ORDER BY sort_order");

// Latest 3 news
$latestNews = DB::fetchAll(
  "SELECT n.*,c.name cat_name FROM news_articles n
   LEFT JOIN news_categories c ON n.category_id=c.id
   WHERE n.is_published=1 ORDER BY n.published_at DESC LIMIT 3"
);

require_once __DIR__.'/includes/header.php';
?>

<!-- ══ HERO SLIDER ════════════════════════════════════════════ -->
<section class="hero-slider">
  <div class="slider-giant">MAF</div>
  <?php foreach($slides as $i=>$sl): ?>
  <div class="slide">
    <div class="slide-bg" style="background-image:url('<?= $sl['image'] ? mediaUrl($sl['image']) : SITE_URL.'/public/images/hero-default.jpg' ?>')"></div>
    <div class="slide-content">
      <div class="slide-eyebrow"><?=e(setting('site_tagline'))?></div>
      <h1 class="slide-title"><?=e($sl['title'])?></h1>
      <?php if($sl['subtitle']): ?>
      <p class="slide-desc"><strong><?=e($sl['subtitle'])?></strong></p>
      <?php endif; ?>
      <?php if($sl['description']): ?>
      <p class="slide-desc"><?=e($sl['description'])?></p>
      <?php endif; ?>
      <div class="slide-actions">
        <?php if($sl['btn_text']&&$sl['btn_link']): ?>
        <a href="<?=SITE_URL.'/'.$sl['btn_link']?>" class="btn btn-red"><?=e($sl['btn_text'])?></a>
        <?php endif; ?>
        <a href="<?=SITE_URL?>/pages/contact.php" class="btn btn-outline">Contact Us</a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <!-- Controls -->
  <div class="slider-controls">
    <button class="slider-prev" aria-label="Previous">&#8592;</button>
    <?php foreach($slides as $i=>$sl): ?>
    <div class="slider-dot<?=$i===0?' active':''?>"></div>
    <?php endforeach; ?>
    <button class="slider-next" aria-label="Next">&#8594;</button>
  </div>
</section>

<!-- ══ TICKER ════════════════════════════════════════════════ -->
<?php if($ticker): ?>
<div class="ticker-wrap" aria-hidden="true">
  <div class="ticker-inner">
    <?php foreach(array_merge($ticker,$ticker) as $t): ?>
    <div class="ticker-item"><span class="ticker-dot"></span><?=e($t['text'])?></div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- ══ STATS BAR ═════════════════════════════════════════════ -->
<?php if($stats): ?>
<section class="stats-bar">
  <div class="stats-bar-grid">
    <?php foreach($stats as $s): ?>
    <div class="stat-cell fu">
      <div class="stat-num">
        <span class="counter" data-target="<?=e($s['value'])?>">0</span>
        <?php if($s['suffix']): ?><span class="suf"><?=e($s['suffix'])?></span><?php endif; ?>
      </div>
      <div class="stat-lbl"><?=e($s['label'])?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ══ INTRO SECTION ═════════════════════════════════════════ -->
<?php if($intro): ?>
<section class="intro-section">
  <div class="container">
    <div class="intro-grid">
      <div class="fu">
        <div class="eyebrow">About MAF Footwear</div>
        <h2 class="sec-title"><?=e($intro['heading'])?></h2>
        <div class="intro-text-content" style="margin-top:24px"><?=$intro['content']?></div>
        <div style="margin-top:36px;display:flex;gap:14px;flex-wrap:wrap">
          <a href="<?=SITE_URL?>/pages/about/company-overview.php" class="btn btn-red">Company Overview</a>
          <a href="<?=SITE_URL?>/pages/products.php" class="btn btn-dark">View Products</a>
        </div>
      </div>
      <div class="fu" style="position:relative;padding-right:24px">
    <!-- old line /*--   <div class="intro-img">
          <img src="<?=SITE_URL?>/public/images/factory-default.jpg" alt="MAF Footwear Factory" loading="lazy"
               onerror="this.src='https://placehold.co/700x480/1C1C1C/CC1E1E?text=MAF+Shoes+Factory'"/>
          <div class="intro-img-badge">   --*/ -->
           <!-- /*new line */-->
            <div class="intro-img">
          <?php $introImg = setting('home_intro_image',''); ?>
<?php if($introImg && is_file(UPLOAD_DIR.$introImg)): ?>
<img src="<?=e(mediaUrl($introImg))?>" alt="MAF Footwear Factory" loading="lazy"/>
<?php else: ?>
<img src="<?=SITE_URL?>/public/images/factory-default.jpg" alt="MAF Footwear Factory" loading="lazy"
     onerror="this.src='https://placehold.co/700x480/1C1C1C/CC1E1E?text=MAF+Shoes+Factory'"/>
<?php endif; ?>
<div class="intro-img-badge"> 
<!--/*new line */-->
            <div class="big"><?=date('Y')-2007?>+</div>
            <div class="sm">Years of Excellence</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ══ QUICK LINKS (Production / Operation / Products) ════════ -->
<section class="quick-links-section">
  <div class="ql-grid">
    <?php
    $qlDefs = [
      ['tag'=>'Production Units','title'=>'Our Vertical Production Units','link'=>'pages/production/index.php','desc'=>'7 integrated production units under one factory roof'],
      ['tag'=>'Operation Strength','title'=>'Our Operation Strength','link'=>'pages/operation/index.php','desc'=>'Skilled people, PDC, QC, Lab, Compliance & Training'],
      ['tag'=>'Products','title'=>'Our Product Catalog','link'=>'pages/products.php','desc'=>'Athletic, Casual, Flip Flops, Sandals & Sports Footwear'],
    ];
    foreach($qlDefs as $i=>$ql):
    ?>
    <div class="ql-card fu">
      <div class="ql-card-img">
        <img src="<?=SITE_URL?>/public/images/ql-<?=$i+1?>-default.jpg" alt="<?=e($ql['title'])?>"
             onerror="this.parentElement.style.display='none'"/>
      </div>
      <div class="ql-card-overlay"></div>
      <div class="ql-card-num"><?=str_pad($i+1,2,'0',STR_PAD_LEFT)?></div>
      <span class="ql-card-tag"><?=e($ql['tag'])?></span>
      <h3 class="ql-card-title"><?=e($ql['title'])?></h3>
      <p style="position:relative;z-index:2;font-size:13px;color:rgba(255,255,255,.5);margin-bottom:16px;line-height:1.6"><?=e($ql['desc'])?></p>
      <a href="<?=SITE_URL?>/<?=e($ql['link'])?>" class="ql-card-link">Learn More <span class="arr">→</span></a>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ══ PRODUCTION UNITS PREVIEW ══════════════════════════════ -->
<?php if($prodUnits): ?>
<section class="section-pad" style="background:var(--gray-bg)">
  <div class="container">
    <div class="fu" style="margin-bottom:48px;display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:20px">
      <div>
        <div class="eyebrow">Vertical Integration</div>
        <h2 class="sec-title">Production Units</h2>
        <p class="sec-desc">Only shoe factory who produces various footwear related finish products and semi-finished products in same factory premises.</p>
      </div>
      <a href="<?=SITE_URL?>/pages/production/index.php" class="btn btn-dark">View All Units</a>
    </div>
    <div class="unit-grid">
      <?php foreach(array_slice($prodUnits,0,6) as $u): ?>
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
          <div style="font-family:var(--font-h);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--red);margin-bottom:8px">Unit 0<?=e($u['sort_order'])?></div>
          <div class="unit-card-title"><?=e($u['title'])?></div>
          <div class="unit-card-desc"><?=e(substr($u['short_description'],0,100))?>…</div>
          <div class="unit-card-link">Details <span>→</span></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ══ EXPORT MARKETS STRIP ══════════════════════════════════ -->
<?php if($markets): ?>
<section class="markets-strip">
  <div class="container">
    <div class="fu" style="text-align:center;margin-bottom:40px">
      <div class="eyebrow" style="justify-content:center">Our Export Destinations</div>
      <h2 class="sec-title light">Global Footprint</h2>
    </div>
    <div class="markets-grid fu">
      <?php foreach($markets as $m): ?>
      <div class="mkt-item">
        <div class="mkt-flag"><?=e($m['flag_emoji'])?></div>
        <div class="mkt-name"><?=e($m['country'])?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ══ LATEST NEWS ════════════════════════════════════════════ -->
<?php if($latestNews): ?>
<section class="section-pad">
  <div class="container">
    <div class="fu" style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:48px;flex-wrap:wrap;gap:20px">
      <div>
        <div class="eyebrow">Media</div>
        <h2 class="sec-title">Latest News</h2>
      </div>
      <a href="<?=SITE_URL?>/pages/media/news.php" class="btn btn-dark">All News →</a>
    </div>
    <div class="news-grid">
      <?php foreach($latestNews as $n): ?>
      <div class="news-card fu">
        <div class="nc-img">
          <?php if($n['thumbnail']): ?>
          <img src="<?=mediaUrl($n['thumbnail'])?>" alt="<?=e($n['title'])?>" loading="lazy"/>
          <?php else: ?>
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1C1C1C 0%,#2E1010 100%)">
            <svg width="56" height="35" viewBox="0 0 90 58" fill="none"><path d="M4 48 C20 48,30 12,48 12 C58 12,64 28,86 20" stroke="#CC1E1E" stroke-width="4" stroke-linecap="round" fill="none"/></svg>
          </div>
          <?php endif; ?>
        </div>
        <div class="nc-body">
          <span class="nc-cat"><?=e($n['cat_name']??'News')?></span>
          <div class="nc-date"><?=fmtDate($n['published_at'])?></div>
          <h3 class="nc-title"><?=e($n['title'])?></h3>
          <p class="nc-excerpt"><?=e($n['excerpt'])?></p>
          <a href="<?=SITE_URL?>/pages/media/news-detail.php?slug=<?=e($n['slug'])?>" class="nc-more">Read More →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ══ CAREER BANNER ════════════════════════════════════════ -->
<section style="background:var(--red);padding:72px 0;position:relative;overflow:hidden">
  <div style="position:absolute;right:56px;top:50%;transform:translateY(-50%);font-family:var(--font-h);font-size:180px;font-weight:900;color:rgba(255,255,255,.06);letter-spacing:-8px;pointer-events:none;white-space:nowrap">CAREER</div>
  <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:32px;flex-wrap:wrap">
    <div class="fu">
      <div class="eyebrow" style="color:rgba(255,255,255,.7)"><span style="background:rgba(255,255,255,.7)"></span>Join Our Team</div>
      <h2 class="sec-title light" style="font-size:clamp(24px,4vw,48px)">Build Your Career<br/>With MAF Footwear</h2>
      <p style="font-size:15px;color:rgba(255,255,255,.7);margin-top:12px;max-width:440px;line-height:1.6">
        Join our team of 5,000+ skilled professionals. We're always looking for talented individuals to grow with us.
      </p>
    </div>
    <a href="<?=SITE_URL?>/pages/jobs.php" class="btn btn-white fu">View Open Positions →</a>
  </div>
</section>

<!-- ══ CONTACT CTA ══════════════════════════════════════════ -->
<section style="background:var(--dark);padding:72px 0">
  <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:32px;flex-wrap:wrap">
    <div class="fu">
      <div class="eyebrow">Get In Touch</div>
      <h2 class="sec-title light">Ready to Work<br/><em style="color:var(--red);font-style:normal">Together?</em></h2>
      <p class="sec-desc light">Whether you're a buyer, brand, or business partner — we'd love to hear from you.</p>
    </div>
    <div class="fu" style="display:flex;gap:14px;flex-wrap:wrap">
      <a href="<?=SITE_URL?>/pages/contact.php" class="btn btn-red">Contact Us →</a>
      <a href="mailto:<?=e(setting('site_email'))?>" class="btn btn-outline">Email Directly</a>
    </div>
  </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
  <span class="lightbox-close">&times;</span>
  <img class="lightbox-img" id="lightboxImg" src="" alt=""/>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
