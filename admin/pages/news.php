<?php
$pageTitle='News Articles';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';

if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){
    if(verifyCsrf($_GET['t']??'')){
        $r=DB::fetchOne("SELECT thumbnail FROM news_articles WHERE id=?",[(int)$_GET['id']]);
        if($r&&$r['thumbnail']) @unlink(UPLOAD_DIR.$r['thumbnail']);
        DB::execute("DELETE FROM news_articles WHERE id=?",[(int)$_GET['id']]);
        flash('success','Article deleted.');
    }
    redirect(SITE_URL.'/admin/pages/news.php');
}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){
    DB::execute("UPDATE news_articles SET is_published=NOT is_published WHERE id=?",[(int)$_GET['id']]);
    redirect(SITE_URL.'/admin/pages/news.php');
}
if(($_GET['action']??'')==='feature'&&isset($_GET['id'])){
    DB::execute("UPDATE news_articles SET is_featured=0");
    DB::execute("UPDATE news_articles SET is_featured=1 WHERE id=?",[(int)$_GET['id']]);
    flash('success','Featured article updated.');
    redirect(SITE_URL.'/admin/pages/news.php');
}

$articles=DB::fetchAll("SELECT n.*,c.name cat_name FROM news_articles n LEFT JOIN news_categories c ON n.category_id=c.id ORDER BY n.created_at DESC");
?>
<?=flashHtml()?>
<div class="pg-header">
  <div><div class="pg-title">News Articles</div><div class="pg-sub">Manage news, press releases and corporate announcements.</div></div>
  <a href="<?=SITE_URL?>/admin/pages/news-edit.php" class="adm-btn adm-btn-red">+ New Article</a>
</div>
<div class="panel">
  <div class="panel-body np">
    <table class="adm-tbl">
      <thead><tr><th>Title</th><th>Category</th><th>Featured</th><th>Status</th><th>Published</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($articles as $a): ?>
      <tr>
        <td style="max-width:280px">
          <div style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:260px"><?=e($a['title'])?></div>
          <div style="margin-top:3px"><a href="<?=SITE_URL?>/pages/media/news-detail.php?slug=<?=e($a['slug'])?>" target="_blank" style="font-size:11px;color:var(--red)">↗ View</a></div>
        </td>
        <td><span class="bdg bdg-gray"><?=e($a['cat_name']??'—')?></span></td>
        <td><?php if($a['is_featured']): ?><span class="bdg bdg-blue">⭐ Featured</span><?php else: ?><a href="?action=feature&id=<?=$a['id']?>" style="font-size:11px;color:var(--muted)">Set</a><?php endif; ?></td>
        <td><a href="?action=toggle&id=<?=$a['id']?>"><span class="bdg <?=$a['is_published']?'bdg-green':'bdg-red'?>"><?=$a['is_published']?'Live':'Draft'?></span></a></td>
        <td style="font-size:12px;color:var(--muted);white-space:nowrap"><?=fmtDate($a['published_at'],'M j, Y')?></td>
        <td>
          <div class="act-btns">
            <a href="<?=SITE_URL?>/admin/pages/news-edit.php?id=<?=$a['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a>
            <a href="?action=delete&id=<?=$a['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete this article permanently?">Delete</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(!$articles): ?><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No articles. <a href="<?=SITE_URL?>/admin/pages/news-edit.php" style="color:var(--red)">Create one →</a></td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
