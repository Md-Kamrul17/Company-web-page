<?php
define('PAGE_SLUG','media-gallery');
require_once __DIR__.'/../../includes/functions.php';
$albumId = (int)($_GET['album']??0);
$albums  = DB::fetchAll("SELECT a.*,(SELECT COUNT(*) FROM gallery_images WHERE album_id=a.id) img_count FROM gallery_albums a WHERE a.is_active=1 ORDER BY a.sort_order");
$images  = $albumId ? DB::fetchAll("SELECT * FROM gallery_images WHERE album_id=? ORDER BY sort_order,id",[$albumId]) : [];
$curAlbum= $albumId ? DB::fetchOne("SELECT * FROM gallery_albums WHERE id=?",[$albumId]) : null;
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb">
      <a href="<?=SITE_URL?>">Home</a><span>/</span>
      <a href="#">Media</a><span>/</span>
      <?php if($curAlbum): ?><a href="<?=SITE_URL?>/pages/media/gallery.php">Gallery</a><span>/</span><?=e($curAlbum['title'])?>
      <?php else: ?>Gallery<?php endif; ?>
    </div>
    <div class="eyebrow">Media</div>
    <h1 class="sec-title" style="color:#fff"><?=$curAlbum?e($curAlbum['title']):'Photo Gallery'?></h1>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <?php if($curAlbum && $images): ?>
    <!-- Single album view -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;flex-wrap:wrap;gap:16px">
      <div>
        <div class="eyebrow"><?=e($curAlbum['title'])?></div>
        <p style="font-size:14px;color:var(--gray)"><?=count($images)?> photos</p>
      </div>
      <a href="<?=SITE_URL?>/pages/media/gallery.php" class="btn btn-dark">← All Albums</a>
    </div>
    <div class="gallery-img-grid">
      <?php foreach($images as $img): ?>
      <div class="gal-img fu" data-lightbox="<?=mediaUrl($img['image'])?>" title="<?=e($img['caption']??'')?>">
        <img src="<?=mediaUrl($img['image'])?>" alt="<?=e($img['caption']??'Gallery image')?>" loading="lazy"
             onerror="this.src='https://placehold.co/400x300/1C1C1C/CC1E1E?text=MAF+Shoes'"/>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Albums list -->
    <div class="fu" style="margin-bottom:48px;text-align:center">
      <div class="eyebrow" style="justify-content:center">Our Collection</div>
      <h2 class="sec-title">Photo Albums</h2>
    </div>
    <div class="album-grid">
      <?php foreach($albums as $alb): ?>
      <a href="?album=<?=$alb['id']?>" class="album-card fu">
        <?php if($alb['cover_image']): ?>
        <img src="<?=mediaUrl($alb['cover_image'])?>" alt="<?=e($alb['title'])?>" loading="lazy"/>
        <?php else: ?>
        <div style="width:100%;height:100%;background:linear-gradient(135deg,#1C1C1C,#2E1010);display:flex;align-items:center;justify-content:center">
          <svg width="60" height="38" viewBox="0 0 90 58" fill="none"><path d="M4 48 C20 48,30 12,48 12 C58 12,64 28,86 20" stroke="#CC1E1E" stroke-width="4" stroke-linecap="round" fill="none"/></svg>
        </div>
        <?php endif; ?>
        <div class="album-overlay">
          <div class="album-title"><?=e($alb['title'])?></div>
          <div class="album-count"><?=$alb['img_count']?> photo<?=$alb['img_count']!=1?'s':''?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php if(!$albums): ?>
    <div style="text-align:center;padding:80px;background:var(--gray-bg)"><p style="font-family:var(--font-h);font-size:14px;color:var(--gray)">Gallery albums coming soon.</p></div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<div class="lightbox" id="lightbox">
  <span class="lightbox-close">&times;</span>
  <img class="lightbox-img" id="lightboxImg" src="" alt=""/>
</div>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
