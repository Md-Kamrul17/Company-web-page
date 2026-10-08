<?php
$pageTitle='Company Pages';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
$editSlug=$_GET['slug']??'company-overview';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $slug=clean($_POST['slug']??'');$title=clean($_POST['page_title']??'');$content=$_POST['content']??'';
    $existing=DB::fetchOne("SELECT * FROM company_pages WHERE page_slug=?",[$slug]);
    if($existing)DB::execute("UPDATE company_pages SET page_title=?,content=? WHERE page_slug=?",[$title,$content,$slug]);
    else DB::execute("INSERT INTO company_pages (page_slug,page_title,content) VALUES (?,?,?)",[$slug,$title,$content]);
    flash('success','Page updated.');redirect(SITE_URL.'/admin/pages/company-pages.php?slug='.$slug);
}
$page=DB::fetchOne("SELECT * FROM company_pages WHERE page_slug=?",[$editSlug]);
$pages=DB::fetchAll("SELECT page_slug,page_title FROM company_pages ORDER BY page_slug");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Company Pages</div></div>
<div style="display:grid;grid-template-columns:200px 1fr;gap:20px">
  <div class="panel" style="height:fit-content">
    <div class="panel-head"><span class="panel-title">Pages</span></div>
    <div class="panel-body np">
      <?php $allSlugs=[['company-overview','Company Overview'],['company-profile','Company Profile']];
      foreach($allSlugs as [$sl,$lbl]): ?>
      <a href="?slug=<?=$sl?>" style="display:block;padding:11px 16px;font-family:var(--font-h);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:<?=$editSlug===$sl?'var(--red)':'var(--dark)'?>;border-bottom:1px solid var(--border);border-left:<?=$editSlug===$sl?'3px solid var(--red)':'3px solid transparent'?>"><?=$lbl?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="panel">
    <div class="panel-head"><span class="panel-title">Edit: <?=e($editSlug)?></span></div>
    <div class="panel-body">
      <form method="POST"><?=csrfField()?>
        <input type="hidden" name="slug" value="<?=e($editSlug)?>"/>
        <div class="adm-fg"><label class="adm-fl">Page Title</label><input class="adm-fi" name="page_title" value="<?=e($page['page_title']??ucwords(str_replace('-',' ',$editSlug)))?>" required/></div>
        <div class="adm-fg">
          <label class="adm-fl">Page Content (HTML)</label>
          <textarea class="adm-ft" name="content" rows="24" style="font-family:monospace;font-size:13px"><?=e($page['content']??'')?></textarea>
          <div class="html-note">⚠ HTML supported: &lt;p&gt;, &lt;h3&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;table&gt;, &lt;strong&gt; etc.</div>
        </div>
        <button type="submit" class="adm-btn adm-btn-red">💾 Save Page</button>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
