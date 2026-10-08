<?php /* operation.php */
$pageTitle='Operation Strength';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM operation_strength WHERE id=?",[(int)$_GET['id']]);flash('success','Deleted.');redirect(SITE_URL.'/admin/pages/operation.php');}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){DB::execute("UPDATE operation_strength SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);redirect(SITE_URL.'/admin/pages/operation.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $id=(int)($_POST['id']??0);$title=clean($_POST['title']??'');$sl=clean($_POST['slug']??'')?:slug($title);$short=clean($_POST['short_description']??'');$content=$_POST['content']??'';$sort=(int)($_POST['sort_order']??0);$active=isset($_POST['is_active'])?1:0;
    $existing=$id?DB::fetchOne("SELECT image,cover_image FROM operation_strength WHERE id=?",[$id]):null;
    $img=$existing['image']??null;$cover=$existing['cover_image']??null;
    if(!empty($_FILES['image']['name'])){$up=uploadFile($_FILES['image'],'operation');if($up){if($img)@unlink(UPLOAD_DIR.$img);$img=$up;}}
    if(!empty($_FILES['cover_image']['name'])){$up=uploadFile($_FILES['cover_image'],'operation');if($up){if($cover)@unlink(UPLOAD_DIR.$cover);$cover=$up;}}
    if($id){DB::execute("UPDATE operation_strength SET title=?,slug=?,short_description=?,content=?,image=?,cover_image=?,sort_order=?,is_active=? WHERE id=?",[$title,$sl,$short,$content,$img,$cover,$sort,$active,$id]);flash('success','Updated.');}
    else{DB::execute("INSERT INTO operation_strength (title,slug,short_description,content,image,cover_image,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?)",[$title,$sl,$short,$content,$img,$cover,$sort,$active]);flash('success','Added.');}
    redirect(SITE_URL.'/admin/pages/operation.php');
}
$editId=(int)($_GET['edit']??0);$editUnit=$editId?DB::fetchOne("SELECT * FROM operation_strength WHERE id=?",[$editId]):null;
$units=DB::fetchAll("SELECT * FROM operation_strength ORDER BY sort_order");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Operation Strength</div><a href="?edit=0" class="adm-btn adm-btn-red">+ Add Area</a></div>
<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editId?'Edit':'Add'?> Operation Area</span><a href="<?=SITE_URL?>/admin/pages/operation.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data"><?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Title *</label><input class="adm-fi" id="titleField" name="title" value="<?=e($editUnit['title']??'')?>" required/></div>
        <div class="adm-fg"><label class="adm-fl">URL Slug</label><input class="adm-fi" id="slugField" name="slug" value="<?=e($editUnit['slug']??'')?>"/></div>
      </div>
      <div class="adm-fg"><label class="adm-fl">Short Description</label><textarea class="adm-ft" name="short_description" rows="3"><?=e($editUnit['short_description']??'')?></textarea></div>
      <div class="adm-fg"><label class="adm-fl">Full Content (HTML)</label><textarea class="adm-ft" name="content" rows="12" style="font-family:monospace;font-size:13px"><?=e($editUnit['content']??'')?></textarea><div class="html-note">Accepts HTML.</div></div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Card Image</label><?php if($editUnit['image']??null): ?><img src="<?=mediaUrl($editUnit['image'])?>" class="img-preview"/><div style="margin-top:8px"></div><?php endif; ?><input class="adm-fi" type="file" name="image" accept="image/*"/></div>
        <div class="adm-fg"><label class="adm-fl">Detail Image</label><?php if($editUnit['cover_image']??null): ?><img src="<?=mediaUrl($editUnit['cover_image'])?>" class="img-preview"/><div style="margin-top:8px"></div><?php endif; ?><input class="adm-fi" type="file" name="cover_image" accept="image/*"/></div>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=$editUnit['sort_order']??count($units)+1?>"/></div>
        <div class="adm-fg" style="display:flex;align-items:flex-end;padding-bottom:18px"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editUnit['is_active']??1)?'checked':''?>> Active</label></div>
      </div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Add'?></button>
    </form>
  </div>
</div>
<?php endif; ?>
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl"><thead><tr><th>#</th><th>Image</th><th>Title</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach($units as $u): ?>
  <tr>
    <td style="color:var(--muted);font-weight:700"><?=$u['sort_order']?></td>
    <td><?php if($u['image']): ?><img src="<?=mediaUrl($u['image'])?>" class="tbl-img"/><?php else: ?><div style="width:56px;height:40px;background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:18px">⚙️</div><?php endif; ?></td>
    <td style="font-weight:600"><?=e($u['title'])?></td>
    <td style="font-size:11px;color:var(--muted)"><?=e($u['slug'])?></td>
    <td><a href="?action=toggle&id=<?=$u['id']?>"><span class="bdg <?=$u['is_active']?'bdg-green':'bdg-red'?>"><?=$u['is_active']?'Active':'Hidden'?></span></a></td>
    <td><div class="act-btns"><a href="?edit=<?=$u['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a><a href="?action=delete&id=<?=$u['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete?">Del</a></div></td>
  </tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
