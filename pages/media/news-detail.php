<?php
define('PAGE_SLUG','media-news');
require_once __DIR__.'/../../includes/functions.php';
$slug = clean($_GET['slug']??'');
if(!$slug) redirect(SITE_URL.'/pages/media/news.php');
$article = DB::fetchOne("SELECT n.*,c.name cat_name FROM news_articles n LEFT JOIN news_categories c ON n.category_id=c.id WHERE n.slug=? AND n.is_published=1",[$slug]);
if(!$article){ http_response_code(404); redirect(SITE_URL.'/pages/media/news.php'); }
$related = DB::fetchAll("SELECT n.*,c.name cat_name FROM news_articles n LEFT JOIN news_categories c ON n.category_id=c.id WHERE n.is_published=1 AND n.id!=? ORDER BY n.published_at DESC LIMIT 3",[$article['id']]);
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero" style="min-height:360px">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="<?=SITE_URL?>/pages/media/news.php">News</a><span>/</span><?=e($article['cat_name']??'News')?></div>
    <div style="font-family:var(--font-h);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:var(--red);margin-bottom:14px"><?=e($article['cat_name']??'News')?></div>
    <h1 style="font-family:var(--font-h);font-size:clamp(22px,4vw,44px);font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:-1px;line-height:1.05;max-width:800px;margin-bottom:16px"><?=e($article['title'])?></h1>
    <div style="font-family:var(--font-h);font-size:11px;color:rgba(255,255,255,.4)"><?=fmtDate($article['published_at'],'F j, Y')?></div>
  </div>
</section>
<section style="padding:72px 0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:56px;align-items:start">
      <div class="fu">
        <?php if($article['thumbnail']): ?>
        <img src="<?=mediaUrl($article['thumbnail'])?>" alt="<?=e($article['title'])?>" style="width:100%;max-height:460px;object-fit:cover;margin-bottom:36px"/>
        <?php endif; ?>
        <?php if($article['excerpt']): ?>
        <p style="font-size:16px;color:#555;line-height:1.75;font-weight:500;margin-bottom:28px;border-left:4px solid var(--red);padding-left:20px"><?=e($article['excerpt'])?></p>
        <?php endif; ?>
        <div class="content-body"><?=$article['content']?></div>
        <div style="margin-top:40px;padding-top:24px;border-top:1px solid rgba(0,0,0,.08);display:flex;gap:12px;align-items:center">
          <span style="font-family:var(--font-h);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--gray)">Share:</span>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode(SITE_URL.'/pages/media/news-detail.php?slug='.$slug)?>" target="_blank" class="btn btn-dark" style="padding:8px 16px;font-size:10px">Facebook</a>
          <a href="https://www.linkedin.com/shareArticle?url=<?=urlencode(SITE_URL.'/pages/media/news-detail.php?slug='.$slug)?>" target="_blank" class="btn btn-dark" style="padding:8px 16px;font-size:10px">LinkedIn</a>
        </div>
      </div>
      <div>
        <?php if($related): ?>
        <div style="background:var(--gray-bg);padding:28px;margin-bottom:24px">
          <div style="font-family:var(--font-h);font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:20px;color:var(--dark)">Related News</div>
          <?php foreach($related as $r): ?>
          <a href="?slug=<?=e($r['slug'])?>" style="display:block;padding:14px 0;border-bottom:1px solid rgba(0,0,0,.07)">
            <div style="font-family:var(--font-h);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--red);margin-bottom:5px"><?=e($r['cat_name']??'News')?></div>
            <div style="font-family:var(--font-h);font-size:12px;font-weight:700;text-transform:uppercase;color:var(--dark);line-height:1.3"><?=e($r['title'])?></div>
            <div style="font-size:11px;color:var(--gray);margin-top:4px"><?=fmtDate($r['published_at'],'M j, Y')?></div>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div style="background:var(--dark);padding:28px;border-top:3px solid var(--red)">
          <div style="font-family:var(--font-h);font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--red);margin-bottom:12px">Contact MAF Footwear</div>
          <p style="font-size:13px;color:rgba(255,255,255,.45);line-height:1.6;margin-bottom:18px">For enquiries, partnerships or media requests.</p>
          <a href="<?=SITE_URL?>/pages/contact.php" class="btn btn-red" style="display:block;text-align:center">Get In Touch</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
