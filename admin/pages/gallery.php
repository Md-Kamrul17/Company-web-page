<?php
$pageTitle='Gallery';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';

// --- ALBUM CRUD ---
if(($_GET['action']??'')==='del-album'&&isset($_GET['id'])){
    if(verifyCsrf($_GET['t']??'')){
        $imgs=DB::fetchAll("SELECT image FROM gallery_images WHERE album_id=?",[(int)$_GET['id']]);
        foreach($imgs as $img) @unlink(UPLOAD_DIR.$img['image']);
        DB::execute("DELETE FROM gallery_albums WHERE id=?",[(int)$_GET['id']]);
        flash('success','Album deleted.');
    }
    redirect(SITE_URL.'/admin/pages/gallery.php');
}
if(($_GET['action']??'')==='del-img'&&isset($_GET['id'])){
    if(verifyCsrf($_GET['t']??'')){
        $r=DB::fetchOne("SELECT image,album_id FROM gallery_images WHERE id=?",[(int)$_GET['id']]);
        if($r)@unlink(UPLOAD_DIR.$r['image']);
        DB::execute("DELETE FROM gallery_images WHERE id=?",[(int)$_GET['id']]);
        flash('success','Image deleted.');
        if($r) redirect(SITE_URL.'/admin/pages/gallery.php?album='.$r['album_id']);
    }
    redirect(SITE_URL.'/admin/pages/gallery.php');
}

// Save/edit album
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['form']??'')==='album'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $id=(int)($_POST['id']??0);$title=clean($_POST['title']??'');$desc=clean($_POST['description']??'');$sort=(int)($_POST['sort_order']??0);$active=isset($_POST['is_active'])?1:0;
    $existing=$id?DB::fetchOne("SELECT cover_image FROM gallery_albums WHERE id=?",[$id]):null;
    $cover=$existing['cover_image']??null;
    if(!empty($_FILES['cover_image']['name'])){$up=uploadFile($_FILES['cover_image'],'gallery');if($up){if($cover)@unlink(UPLOAD_DIR.$cover);$cover=$up;}}
    if($id){DB::execute("UPDATE gallery_albums SET title=?,description=?,cover_image=?,sort_order=?,is_active=? WHERE id=?",[$title,$desc,$cover,$sort,$active,$id]);flash('success','Album updated.');}
    else{DB::execute("INSERT INTO gallery_albums (title,description,cover_image,sort_order,is_active) VALUES (?,?,?,?,?)",[$title,$desc,$cover,$sort,$active]);flash('success','Album created.');}
    redirect(SITE_URL.'/admin/pages/gallery.php');
}
// Upload images to album
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['form']??'')==='images'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $albumId=(int)($_POST['album_id']??0);
    if($albumId&&!empty($_FILES['images']['name'][0])){
        $count=count($_FILES['images']['name']);
        for($i=0;$i<$count;$i++){
            $file=['name'=>$_FILES['images']['name'][$i],'type'=>$_FILES['images']['type'][$i],'tmp_name'=>$_FILES['images']['tmp_name'][$i],'size'=>$_FILES['images']['size'][$i],'error'=>$_FILES['images']['error'][$i]];
            if($file['error']===0){
                $up=uploadFile($file,'gallery');
                if($up)DB::execute("INSERT INTO gallery_images (album_id,image,caption,sort_order) VALUES (?,?,?,0)",[$albumId,$up,clean($_POST['caption']??'')]);
            }
        }
        flash('success','Images uploaded successfully.');
    }
    redirect(SITE_URL.'/admin/pages/gallery.php?album='.$albumId);
}

$albumId=(int)($_GET['album']??0);
$albums=DB::fetchAll("SELECT a.*,(SELECT COUNT(*) FROM gallery_images WHERE album_id=a.id) img_count FROM gallery_albums a ORDER BY a.sort_order");
$editAlbumId=(int)($_GET['edit']??0);$editAlbum=$editAlbumId?DB::fetchOne("SELECT * FROM gallery_albums WHERE id=?",[$editAlbumId]):null;
$currentAlbum=$albumId?DB::fetchOne("SELECT * FROM gallery_albums WHERE id=?",[$albumId]):null;
$albumImages=$albumId?DB::fetchAll("SELECT * FROM gallery_images WHERE album_id=? ORDER BY sort_order,id",[$albumId]):[];
?>
<?=flashHtml()?>
<div class="pg-header">
  <div class="pg-title">Gallery <?php if($currentAlbum): ?> — <?=e($currentAlbum['title'])?><?php endif; ?></div>
  <div style="display:flex;gap:10px">
    <?php if($albumId): ?><a href="<?=SITE_URL?>/admin/pages/gallery.php" class="adm-btn adm-btn-out">← Albums</a><?php endif; ?>
    <a href="?edit=0" class="adm-btn adm-btn-red">+ Add Album</a>
  </div>
</div>

<!-- Add/Edit Album Form -->
<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editAlbumId?'Edit':'Create'?> Album</span><a href="<?=SITE_URL?>/admin/pages/gallery.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data"><?=csrfField()?><input type="hidden" name="form" value="album"/><input type="hidden" name="id" value="<?=$editAlbumId?>"/>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Album Title *</label><input class="adm-fi" name="title" value="<?=e($editAlbum['title']??'')?>" required/></div>
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=$editAlbum['sort_order']??count($albums)+1?>"/></div>
      </div>
      <div class="adm-fg"><label class="adm-fl">Description</label><textarea class="adm-ft" name="description" rows="2"><?=e($editAlbum['description']??'')?></textarea></div>
      <div class="adm-fg">
        <label class="adm-fl">Cover Image</label>
        <?php if($editAlbum['cover_image']??null): ?><img src="<?=mediaUrl($editAlbum['cover_image'])?>" class="img-preview"/><div style="margin-top:8px"></div><?php endif; ?>
        <input class="adm-fi" type="file" name="cover_image" accept="image/*"/>
      </div>
      <label class="adm-check"><input type="checkbox" name="is_active" <?=($editAlbum['is_active']??1)?'checked':''?>> Active</label>
      <div style="margin-top:16px"><button type="submit" class="adm-btn adm-btn-red">💾 <?=$editAlbumId?'Update':'Create'?> Album</button></div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php if($albumId && $currentAlbum): ?>
<!-- Upload Images to Album -->
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title">Upload Images to "<?=e($currentAlbum['title'])?>"</span></div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data"><?=csrfField()?><input type="hidden" name="form" value="images"/><input type="hidden" name="album_id" value="<?=$albumId?>"/>
      <div class="adm-fg"><label class="adm-fl">Select Images (multiple)</label><input class="adm-fi" type="file" name="images[]" accept="image/*" multiple required/><p class="adm-hint">You can select multiple images at once. JPEG/PNG/WebP, max 8MB each.</p></div>
      <button type="submit" class="adm-btn adm-btn-red">📤 Upload Images</button>
    </form>
  </div>
</div>
<!-- Album Images Grid -->
<div class="panel">
  <div class="panel-head"><span class="panel-title">Images (<?=count($albumImages)?>)</span></div>
  <div class="panel-body">
    <?php if($albumImages): ?>
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px">
      <?php foreach($albumImages as $img): ?>
      <div style="position:relative">
        <img src="<?=mediaUrl($img['image'])?>" style="width:100%;aspect-ratio:4/3;object-fit:cover;" onerror="this.src='https://placehold.co/200x150/F5F5F5/999?text=?'"/>
        <a href="?action=del-img&id=<?=$img['id']?>&t=<?=csrfToken()?>" data-confirm="Delete this image?" style="position:absolute;top:5px;right:5px;background:rgba(204,30,30,.9);color:#fff;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700">×</a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted);font-size:13px">No images yet. Upload some above.</p>
    <?php endif; ?>
  </div>
</div>
<?php else: ?>
<!-- Albums List -->
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl"><thead><tr><th>#</th><th>Cover</th><th>Title</th><th>Images</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach($albums as $alb): ?>
  <tr>
    <td style="color:var(--muted);font-weight:700"><?=$alb['sort_order']?></td>
    <td><?php if($alb['cover_image']): ?><img src="<?=mediaUrl($alb['cover_image'])?>" class="tbl-img"/><?php else: ?><div style="width:56px;height:40px;background:var(--bg);display:flex;align-items:center;justify-content:center">🖼️</div><?php endif; ?></td>
    <td style="font-weight:600"><?=e($alb['title'])?></td>
    <td><span class="bdg bdg-blue"><?=$alb['img_count']?> photos</span></td>
    <td><span class="bdg <?=$alb['is_active']?'bdg-green':'bdg-red'?>"><?=$alb['is_active']?'Active':'Hidden'?></span></td>
    <td>
      <div class="act-btns">
        <a href="?album=<?=$alb['id']?>" class="adm-btn adm-btn-out adm-btn-sm">📷 Images</a>
        <a href="?edit=<?=$alb['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a>
        <a href="?action=del-album&id=<?=$alb['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete album and all its images?">Del</a>
      </div>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if(!$albums): ?><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No albums yet.</td></tr><?php endif; ?>
  </tbody></table>
</div></div>
<?php endif; ?>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
