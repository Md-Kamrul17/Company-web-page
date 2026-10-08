<?php /* videos.php */
define('PAGE_SLUG','media-videos');
require_once __DIR__.'/../../includes/functions.php';
$videos = DB::fetchAll("SELECT * FROM videos WHERE is_active=1 ORDER BY sort_order,id");
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="#">Media</a><span>/</span>Videos</div>
    <div class="eyebrow">Media</div>
    <h1 class="sec-title" style="color:#fff">Videos</h1>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <?php if($videos): ?>
    <div class="video-grid">
      <?php foreach($videos as $v): ?>
      <div class="video-card fu">
        <div class="video-wrap">
          <?php if($v['video_type']==='youtube'||$v['video_type']==='vimeo'): ?>
          <iframe src="<?=e($v['video_url'])?>" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
          <?php else: ?>
          <video controls preload="metadata" poster="<?=$v['thumbnail']?mediaUrl($v['thumbnail']):''?>">
            <source src="<?=mediaUrl($v['video_url'])?>" type="video/mp4"/>
          </video>
          <?php endif; ?>
        </div>
        <div class="video-info">
          <div class="video-title"><?=e($v['title'])?></div>
          <?php if($v['description']): ?>
          <div class="video-desc"><?=e($v['description'])?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:80px;background:var(--gray-bg)"><p style="font-family:var(--font-h);font-size:14px;color:var(--gray)">Videos coming soon.</p></div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
