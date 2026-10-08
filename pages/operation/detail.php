<?php
define('PAGE_SLUG','operation-detail');
require_once __DIR__.'/../../includes/functions.php';
$slug = clean($_GET['slug']??'');
if(!$slug) redirect(SITE_URL.'/pages/operation/index.php');
$unit = DB::fetchOne("SELECT * FROM operation_strength WHERE slug=? AND is_active=1",[$slug]);
if(!$unit){ http_response_code(404); redirect(SITE_URL.'/pages/operation/index.php'); }
$all  = DB::fetchAll("SELECT id,slug,title FROM operation_strength WHERE is_active=1 ORDER BY sort_order");
$idx  = array_search($unit['id'],array_column($all,'id'));
$prev = $idx>0 ? $all[$idx-1] : null;
$next = ($idx<count($all)-1) ? $all[$idx+1] : null;
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb">
      <a href="<?=SITE_URL?>">Home</a><span>/</span>
      <a href="<?=SITE_URL?>/pages/operation/index.php">Operation Strength</a><span>/</span>
      <?=e($unit['title'])?>
    </div>
    <div class="eyebrow">Operation Strength</div>
    <h1 class="sec-title" style="color:#fff"><?=e($unit['title'])?></h1>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="unit-detail-grid fu">
      <div>
        <div class="eyebrow">Overview</div>
        <h2 class="sec-title" style="font-size:30px;margin-bottom:28px"><?=e($unit['title'])?></h2>
        <div class="content-body"><?=$unit['content']?></div>
        <div style="margin-top:40px;display:flex;gap:14px;flex-wrap:wrap">
          <?php if($prev): ?>
          <a href="?slug=<?=e($prev['slug'])?>" class="btn btn-dark">← <?=e($prev['title'])?></a>
          <?php endif; ?>
          <?php if($next): ?>
          <a href="?slug=<?=e($next['slug'])?>" class="btn btn-red"><?=e($next['title'])?> →</a>
          <?php endif; ?>
        </div>
      </div>
      <div>
        <?php if($unit['image']): ?>
        <div class="unit-detail-img"><img src="<?=mediaUrl($unit['image'])?>" alt="<?=e($unit['title'])?>" loading="lazy"/></div>
        <?php else: ?>
        <div class="unit-detail-img" style="display:flex;align-items:center;justify-content:center">
          <?php $icons=['people'=>'👥','product-development-center'=>'🔬','quality-control'=>'✅','internal-lab'=>'🧪','compliance'=>'📋','training-center'=>'🎓']; ?>
          <span style="font-size:96px"><?=$icons[$slug]??'⚙️'?></span>
        </div>
        <?php endif; ?>
        <div style="background:var(--gray-bg);padding:28px;margin-top:24px">
          <div style="font-family:var(--font-h);font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:16px">All Operation Areas</div>
          <?php foreach($all as $u): ?>
          <a href="?slug=<?=e($u['slug'])?>" style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid rgba(0,0,0,.06);font-family:var(--font-h);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:<?=$u['slug']===$slug?'var(--red)':'var(--dark)'?>">
            <span style="width:6px;height:6px;border-radius:50%;background:<?=$u['slug']===$slug?'var(--red)':'var(--gray-l)'?>;flex-shrink:0"></span>
            <?=e($u['title'])?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
