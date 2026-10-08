<?php
$pageTitle='Videos';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM videos WHERE id=?",[(int)$_GET['id']]);flash('success','Video deleted.');redirect(SITE_URL.'/admin/pages/videos.php');}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){DB::execute("UPDATE videos SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);redirect(SITE_URL.'/admin/pages/videos.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $id=(int)($_POST['id']??0);$title=clean($_POST['title']??'');$desc=clean($_POST['description']??'');$type=clean($_POST['video_type']??'youtube');$url=clean($_POST['video_url']??'');$sort=(int)($_POST['sort_order']??0);$active=isset($_POST['is_active'])?1:0;
    $existing=$id?DB::fetchOne("SELECT thumbnail FROM videos WHERE id=?",[$id]):null;
    $thumb=$existing['thumbnail']??null;
    if(!empty($_FILES['thumbnail']['name'])){$up=uploadFile($_FILES['thumbnail'],'videos');if($up){if($thumb)@unlink(UPLOAD_DIR.$thumb);$thumb=$up;}}
    if($id){DB::execute("UPDATE videos SET title=?,description=?,video_type=?,video_url=?,thumbnail=?,sort_order=?,is_active=? WHERE id=?",[$title,$desc,$type,$url,$thumb,$sort,$active,$id]);flash('success','Video updated.');}
    else{DB::execute("INSERT INTO videos (title,description,video_type,video_url,thumbnail,sort_order,is_active) VALUES (?,?,?,?,?,?,?)",[$title,$desc,$type,$url,$thumb,$sort,$active]);flash('success','Video added.');}
    redirect(SITE_URL.'/admin/pages/videos.php');
}
$editId=(int)($_GET['edit']??0);$editV=$editId?DB::fetchOne("SELECT * FROM videos WHERE id=?",[$editId]):null;
$videos=DB::fetchAll("SELECT * FROM videos ORDER BY sort_order,id");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Videos</div><a href="?edit=0" class="adm-btn adm-btn-red">+ Add Video</a></div>
<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editId?'Edit':'Add'?> Video</span><a href="<?=SITE_URL?>/admin/pages/videos.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data"><?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-fg"><label class="adm-fl">Video Title *</label><input class="adm-fi" name="title" value="<?=e($editV['title']??'')?>" required/></div>
      <div class="adm-fg"><label class="adm-fl">Description</label><textarea class="adm-ft" name="description" rows="3"><?=e($editV['description']??'')?></textarea></div>
      <div class="adm-row">
        <div class="adm-fg">
          <label class="adm-fl">Video Type</label>
          <select class="adm-fs" name="video_type">
            <option value="youtube" <?=($editV['video_type']??'youtube')==='youtube'?'selected':''?>>YouTube Embed</option>
            <option value="vimeo"   <?=($editV['video_type']??'')==='vimeo'?'selected':''?>>Vimeo Embed</option>
            <option value="upload"  <?=($editV['video_type']??'')==='upload'?'selected':''?>>Uploaded File</option>
          </select>
        </div>
        <div class="adm-fg"><label class="adm-fl">Video URL / Embed URL</label><input class="adm-fi" name="video_url" value="<?=e($editV['video_url']??'')?>" placeholder="https://www.youtube.com/embed/..."/><p class="adm-hint">For YouTube: use embed URL. For uploads: use relative path like videos/file.mp4</p></div>
      </div>
      <div class="adm-fg">
        <label class="adm-fl">Thumbnail / Poster Image</label>
        <?php if($editV['thumbnail']??null): ?><img src="<?=mediaUrl($editV['thumbnail'])?>" class="img-preview"/><div style="margin-top:8px"></div><?php endif; ?>
        <input class="adm-fi" type="file" name="thumbnail" accept="image/*"/>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=$editV['sort_order']??count($videos)+1?>"/></div>
        <div class="adm-fg" style="display:flex;align-items:flex-end;padding-bottom:18px"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editV['is_active']??1)?'checked':''?>> Active</label></div>
      </div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Add'?> Video</button>
    </form>
  </div>
</div>
<?php endif; ?>
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl"><thead><tr><th>#</th><th>Thumb</th><th>Title</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach($videos as $v): ?>
  <tr>
    <td style="color:var(--muted);font-weight:700"><?=$v['sort_order']?></td>
    <td><?php if($v['thumbnail']): ?><img src="<?=mediaUrl($v['thumbnail'])?>" class="tbl-img"/><?php else: ?><div style="width:56px;height:40px;background:var(--dark);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px">▶</div><?php endif; ?></td>
    <td style="font-weight:600"><?=e($v['title'])?></td>
    <td><span class="bdg bdg-blue"><?=e($v['video_type'])?></span></td>
    <td><a href="?action=toggle&id=<?=$v['id']?>"><span class="bdg <?=$v['is_active']?'bdg-green':'bdg-red'?>"><?=$v['is_active']?'Active':'Hidden'?></span></a></td>
    <td><div class="act-btns"><a href="?edit=<?=$v['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a><a href="?action=delete&id=<?=$v['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete video?">Del</a></div></td>
  </tr>
  <?php endforeach; ?>
  <?php if(!$videos): ?><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No videos yet.</td></tr><?php endif; ?>
  </tbody></table>
</div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
