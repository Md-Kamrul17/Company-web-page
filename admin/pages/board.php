<?php
$pageTitle='Board of Directors';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){
    if(verifyCsrf($_GET['t']??'')){
        $r=DB::fetchOne("SELECT photo FROM board_members WHERE id=?",[(int)$_GET['id']]);
        if($r&&$r['photo'])@unlink(UPLOAD_DIR.$r['photo']);
        DB::execute("DELETE FROM board_members WHERE id=?",[(int)$_GET['id']]);
        flash('success','Member deleted.');
    }
    redirect(SITE_URL.'/admin/pages/board.php');
}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){DB::execute("UPDATE board_members SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);redirect(SITE_URL.'/admin/pages/board.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $id=(int)($_POST['id']??0);
    $name=clean($_POST['name']??'');$desig=clean($_POST['designation']??'');$bio=clean($_POST['bio']??'');$sort=(int)($_POST['sort_order']??0);$active=isset($_POST['is_active'])?1:0;
    $existing=$id?DB::fetchOne("SELECT photo FROM board_members WHERE id=?",[$id]):null;
    $photo=$existing['photo']??null;
    if(!empty($_FILES['photo']['name'])){$up=uploadFile($_FILES['photo'],'team');if($up){if($photo)@unlink(UPLOAD_DIR.$photo);$photo=$up;}}
    if($id){
        DB::execute("UPDATE board_members SET name=?,designation=?,bio=?,photo=?,sort_order=?,is_active=? WHERE id=?",[$name,$desig,$bio,$photo,$sort,$active,$id]);
        flash('success','Member updated.');
    } else {
        DB::execute("INSERT INTO board_members (name,designation,bio,photo,sort_order,is_active) VALUES (?,?,?,?,?,?)",[$name,$desig,$bio,$photo,$sort,$active]);
        flash('success','Member added.');
    }
    redirect(SITE_URL.'/admin/pages/board.php');
}
$editId=(int)($_GET['edit']??0);
$editM=$editId?DB::fetchOne("SELECT * FROM board_members WHERE id=?",[$editId]):null;
$members=DB::fetchAll("SELECT * FROM board_members ORDER BY sort_order");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Board of Directors</div><a href="?edit=0" class="adm-btn adm-btn-red">+ Add Member</a></div>
<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editId?'Edit':'Add'?> Board Member</span><a href="<?=SITE_URL?>/admin/pages/board.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data"><?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Full Name *</label><input class="adm-fi" name="name" value="<?=e($editM['name']??'')?>" required/></div>
        <div class="adm-fg"><label class="adm-fl">Designation *</label><input class="adm-fi" name="designation" value="<?=e($editM['designation']??'')?>" required/></div>
      </div>
      <div class="adm-fg"><label class="adm-fl">Biography</label><textarea class="adm-ft" name="bio" rows="4"><?=e($editM['bio']??'')?></textarea></div>
      <div class="adm-fg">
        <label class="adm-fl">Photo</label>
        <?php if($editM['photo']??null): ?><img src="<?=mediaUrl($editM['photo'])?>" class="img-preview"/><div style="margin-top:8px"></div><?php endif; ?>
        <input class="adm-fi" type="file" name="photo" accept="image/*"/>
        <p class="adm-hint">Square photo recommended (400×400px). JPEG/PNG/WebP.</p>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=$editM['sort_order']??count($members)+1?>"/></div>
        <div class="adm-fg" style="display:flex;align-items:flex-end;padding-bottom:18px"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editM['is_active']??1)?'checked':''?>> Active</label></div>
      </div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Add'?> Member</button>
    </form>
  </div>
</div>
<?php endif; ?>
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl"><thead><tr><th>#</th><th>Photo</th><th>Name</th><th>Designation</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach($members as $m): ?>
  <tr>
    <td style="color:var(--muted);font-family:var(--font-h);font-weight:700"><?=$m['sort_order']?></td>
    <td>
      <?php if($m['photo']): ?><img src="<?=mediaUrl($m['photo'])?>" style="width:44px;height:44px;object-fit:cover;border-radius:50%"/>
      <?php else: ?><div style="width:44px;height:44px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-family:var(--font-h);font-size:16px;font-weight:900;color:#fff"><?=strtoupper(substr($m['name'],0,1))?></div><?php endif; ?>
    </td>
    <td style="font-weight:600"><?=e($m['name'])?></td>
    <td style="font-size:13px;color:var(--muted)"><?=e($m['designation'])?></td>
    <td><a href="?action=toggle&id=<?=$m['id']?>"><span class="bdg <?=$m['is_active']?'bdg-green':'bdg-red'?>"><?=$m['is_active']?'Active':'Hidden'?></span></a></td>
    <td><div class="act-btns"><a href="?edit=<?=$m['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a><a href="?action=delete&id=<?=$m['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete member?">Del</a></div></td>
  </tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
