<?php /* page-meta.php */
$pageTitle='Page SEO / Meta';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    foreach(($_POST['pages']??[]) as $slug=>$d){
        $t=clean($d['meta_title']??'');$ds=clean($d['meta_desc']??'');
        DB::execute("INSERT INTO page_meta (page_slug,meta_title,meta_description) VALUES (?,?,?) ON DUPLICATE KEY UPDATE meta_title=?,meta_description=?",[$slug,$t,$ds,$t,$ds]);
    }
    flash('success','SEO meta saved.');redirect(SITE_URL.'/admin/pages/page-meta.php');
}
$pages=DB::fetchAll("SELECT * FROM page_meta ORDER BY page_slug");
$pMap=array_column($pages,null,'page_slug');
$pageSlugs=['home','company-overview','company-profile','vision','board','production','operation','products','news','gallery','videos','jobs','contact'];
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Page SEO / Meta Tags</div></div>
<form method="POST">
  <?=csrfField()?>
  <?php foreach($pageSlugs as $sl): $pm=$pMap[$sl]??[]; ?>
  <div class="panel" style="margin-bottom:16px">
    <div class="panel-head"><span class="panel-title" style="text-transform:uppercase"><?=e($sl)?></span></div>
    <div class="panel-body">
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Meta Title</label><input class="adm-fi" name="pages[<?=$sl?>][meta_title]" value="<?=e($pm['meta_title']??'')?>" placeholder="Page title for search engines..."/></div>
        <div class="adm-fg"><label class="adm-fl">Meta Description</label><textarea class="adm-ft" name="pages[<?=$sl?>][meta_desc]" rows="2" placeholder="160 chars for search results..."><?=e($pm['meta_description']??'')?></textarea></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="panel"><div class="panel-body"><button type="submit" class="adm-btn adm-btn-red" style="padding:13px 36px">💾 Save All Meta Tags</button></div></div>
</form>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
