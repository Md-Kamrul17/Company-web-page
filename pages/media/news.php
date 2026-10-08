<?php /* news.php */
define('PAGE_SLUG','media-news');
require_once __DIR__.'/../../includes/functions.php';
$cat    = clean($_GET['cat']??'');
$page   = max(1,(int)($_GET['page']??1));
$perPage= 9;
$cats   = DB::fetchAll("SELECT * FROM news_categories ORDER BY name");
$where  = 'n.is_published=1'; $params=[];
if($cat){ $where.=' AND c.slug=?'; $params[]=$cat; }
$pg = paginate("SELECT COUNT(*) c FROM news_articles n LEFT JOIN news_categories c ON n.category_id=c.id WHERE $where",$page,$perPage,$params);
$articles = DB::fetchAll("SELECT n.*,c.name cat_name,c.slug cat_slug FROM news_articles n LEFT JOIN news_categories c ON n.category_id=c.id WHERE $where ORDER BY n.published_at DESC LIMIT {$perPage} OFFSET {$pg['offset']}",$params);
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="#">Media</a><span>/</span>News</div>
    <div class="eyebrow">Media</div>
    <h1 class="sec-title" style="color:#fff">News &amp; Updates</h1>
  </div>
</section>
<section class="section-pad">
  <div class="container">
    <div class="news-filters fu">
      <a href="<?=SITE_URL?>/pages/media/news.php" class="nf-btn <?=!$cat?'active':''?>">All</a>
      <?php foreach($cats as $c): ?>
      <a href="?cat=<?=e($c['slug'])?>" class="nf-btn <?=$cat===$c['slug']?'active':''?>"><?=e($c['name'])?></a>
      <?php endforeach; ?>
    </div>
    <?php if($articles): ?>
    <div class="news-grid">
      <?php foreach($articles as $a): ?>
      <div class="news-card fu">
        <div class="nc-img">
          <?php if($a['thumbnail']): ?>
          <img src="<?=mediaUrl($a['thumbnail'])?>" alt="<?=e($a['title'])?>" loading="lazy"/>
          <?php else: ?>
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1C1C1C,#2E1010)"><svg width="56" height="35" viewBox="0 0 90 58" fill="none"><path d="M4 48 C20 48,30 12,48 12 C58 12,64 28,86 20" stroke="#CC1E1E" stroke-width="4" stroke-linecap="round" fill="none"/></svg></div>
          <?php endif; ?>
        </div>
        <div class="nc-body">
          <span class="nc-cat"><?=e($a['cat_name']??'News')?></span>
          <div class="nc-date"><?=fmtDate($a['published_at'])?></div>
          <h3 class="nc-title"><?=e($a['title'])?></h3>
          <p class="nc-excerpt"><?=e($a['excerpt'])?></p>
          <a href="<?=SITE_URL?>/pages/media/news-detail.php?slug=<?=e($a['slug'])?>" class="nc-more">Read More →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if($pg['pages']>1): ?>
    <div class="pagination">
      <?php if($pg['current']>1): ?><a class="pg-btn" href="?<?=$cat?"cat=$cat&":""?>page=<?=$pg['current']-1?>">‹</a><?php endif; ?>
      <?php for($i=1;$i<=$pg['pages'];$i++): ?>
      <a class="pg-btn <?=$i===$pg['current']?'active':''?>" href="?<?=$cat?"cat=$cat&":""?>page=<?=$i?>"><?=$i?></a>
      <?php endfor; ?>
      <?php if($pg['current']<$pg['pages']): ?><a class="pg-btn" href="?<?=$cat?"cat=$cat&":""?>page=<?=$pg['current']+1?>">›</a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <div style="text-align:center;padding:80px;background:var(--gray-bg)"><p style="font-family:var(--font-h);font-size:14px;color:var(--gray)">No news articles found.</p></div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
