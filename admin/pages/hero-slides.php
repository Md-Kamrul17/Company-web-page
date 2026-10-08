<?php /* hero-slides.php */
$pageTitle='Hero Slides';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM hero_slides WHERE id=?",[(int)$_GET['id']]);flash('success','Slide deleted.');redirect(SITE_URL.'/admin/pages/hero-slides.php');}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){DB::execute("UPDATE hero_slides SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);redirect(SITE_URL.'/admin/pages/hero-slides.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $id=(int)($_POST['id']??0);$title=clean($_POST['title']??'');$sub=clean($_POST['subtitle']??'');$desc=clean($_POST['description']??'');$btn=clean($_POST['btn_text']??'');$lnk=clean($_POST['btn_link']??'');$sort=(int)($_POST['sort_order']??0);$active=isset($_POST['is_active'])?1:0;
    $existing=$id?DB::fetchOne("SELECT image FROM hero_slides WHERE id=?",[$id]):null;
    $img=$existing['image']??null;
    if(!empty($_FILES['image']['name'])){$up=uploadFile($_FILES['image'],'hero');if($up){if($img)@unlink(UPLOAD_DIR.$img);$img=$up;}}
    if($id){DB::execute("UPDATE hero_slides SET title=?,subtitle=?,description=?,btn_text=?,btn_link=?,image=?,sort_order=?,is_active=? WHERE id=?",[$title,$sub,$desc,$btn,$lnk,$img,$sort,$active,$id]);flash('success','Slide updated.');}
    else{DB::execute("INSERT INTO hero_slides (title,subtitle,description,btn_text,btn_link,image,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?)",[$title,$sub,$desc,$btn,$lnk,$img,$sort,$active]);flash('success','Slide added.');}
    redirect(SITE_URL.'/admin/pages/hero-slides.php');
}
$editId=(int)($_GET['edit']??0);$editSlide=$editId?DB::fetchOne("SELECT * FROM hero_slides WHERE id=?",[$editId]):null;
$slides=DB::fetchAll("SELECT * FROM hero_slides ORDER BY sort_order");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Hero Slides</div><a href="?edit=0" class="adm-btn adm-btn-red">+ Add Slide</a></div>
<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editId?'Edit':'Add'?> Slide</span><a href="<?=SITE_URL?>/admin/pages/hero-slides.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data"><?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-fg"><label class="adm-fl">Main Title *</label><input class="adm-fi" name="title" value="<?=e($editSlide['title']??'')?>" required/></div>
      <div class="adm-fg"><label class="adm-fl">Subtitle</label><input class="adm-fi" name="subtitle" value="<?=e($editSlide['subtitle']??'')?>"/></div>
      <div class="adm-fg"><label class="adm-fl">Description</label><textarea class="adm-ft" name="description" rows="3"><?=e($editSlide['description']??'')?></textarea></div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Button Text</label><input class="adm-fi" name="btn_text" value="<?=e($editSlide['btn_text']??'Learn More')?>"/></div>
        <div class="adm-fg"><label class="adm-fl">Button Link (relative path)</label><input class="adm-fi" name="btn_link" value="<?=e($editSlide['btn_link']??'')?>" placeholder="pages/about/company-overview.php"/></div>
      </div>
      <div class="adm-fg">
        <label class="adm-fl">Background Image</label>
        <?php if($editSlide['image']??null): ?><img src="<?=mediaUrl($editSlide['image'])?>" class="img-preview"/><div style="margin-top:8px"></div><?php endif; ?>
        <input class="adm-fi" type="file" name="image" accept="image/*"/>
        <p class="adm-hint">Recommended: 1920×1080px. JPEG/PNG/WebP.</p>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=$editSlide['sort_order']??count($slides)+1?>"/></div>
        <div class="adm-fg" style="display:flex;align-items:flex-end;padding-bottom:18px"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editSlide['is_active']??1)?'checked':''?>> Active</label></div>
      </div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Add'?> Slide</button>
    </form>
  </div>
</div>
<?php endif; ?>
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl"><thead><tr><th>#</th><th>Image</th><th>Title</th><th>Button</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach($slides as $sl): ?>
  <tr>
    <td style="font-family:var(--font-h);font-weight:700;color:var(--muted)"><?=$sl['sort_order']?></td>
    <td><?php if($sl['image']): ?><img src="<?=mediaUrl($sl['image'])?>" class="tbl-img"/><?php else: ?><div style="width:56px;height:40px;background:var(--bg);display:flex;align-items:center;justify-content:center">🖼️</div><?php endif; ?></td>
    <td style="font-weight:600"><?=e($sl['title'])?></td>
    <td style="font-size:12px;color:var(--muted)"><?=e($sl['btn_text']??'—')?></td>
    <td><a href="?action=toggle&id=<?=$sl['id']?>"><span class="bdg <?=$sl['is_active']?'bdg-green':'bdg-red'?>"><?=$sl['is_active']?'Active':'Hidden'?></span></a></td>
    <td><div class="act-btns"><a href="?edit=<?=$sl['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a><a href="?action=delete&id=<?=$sl['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete slide?">Del</a></div></td>
  </tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
