<?php
$pageTitle='Admin Users';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
$me=currentAdmin();
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){
    if((int)$_GET['id']===$me['id']){flash('error','Cannot delete your own account.');redirect(SITE_URL.'/admin/pages/admins.php');}
    if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM admin_users WHERE id=?",[(int)$_GET['id']]);
    flash('success','Admin deleted.');redirect(SITE_URL.'/admin/pages/admins.php');
}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){
    if((int)$_GET['id']===$me['id']){flash('error','Cannot deactivate your own account.');redirect(SITE_URL.'/admin/pages/admins.php');}
    DB::execute("UPDATE admin_users SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);
    redirect(SITE_URL.'/admin/pages/admins.php');
}
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('Security error.');
    $id=(int)($_POST['id']??0);$name=clean($_POST['name']??'');$email=clean($_POST['email']??'');$role=in_array($_POST['role']??'',['super_admin','editor'])?$_POST['role']:'editor';$pass=$_POST['password']??'';$active=isset($_POST['is_active'])?1:0;
    if(!$name) $errors[]='Name required.';
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Valid email required.';
    if(!$id&&!$pass) $errors[]='Password required for new users.';
    if($pass&&strlen($pass)<8) $errors[]='Password must be at least 8 characters.';
    $dup=DB::fetchOne("SELECT id FROM admin_users WHERE email=? AND id!=?",[$email,$id]);
    if($dup) $errors[]='Email already in use.';
    if(!$errors){
        $hash=$pass?password_hash($pass,PASSWORD_DEFAULT):null;
        if($id){
            if($hash)DB::execute("UPDATE admin_users SET name=?,email=?,role=?,password=?,is_active=? WHERE id=?",[$name,$email,$role,$hash,$active,$id]);
            else DB::execute("UPDATE admin_users SET name=?,email=?,role=?,is_active=? WHERE id=?",[$name,$email,$role,$active,$id]);
            flash('success','Admin updated.');
        } else {
            DB::execute("INSERT INTO admin_users (name,email,password,role,is_active) VALUES (?,?,?,?,?)",[$name,$email,$hash,$role,$active]);
            flash('success','Admin created.');
        }
        redirect(SITE_URL.'/admin/pages/admins.php');
    }
}
$editId=(int)($_GET['edit']??0);$editA=$editId?DB::fetchOne("SELECT * FROM admin_users WHERE id=?",[$editId]):null;
$admins=DB::fetchAll("SELECT * FROM admin_users ORDER BY id");
?>
<?=flashHtml()?>
<?php foreach($errors as $e): ?><div class="adm-alert adm-alert-error">✕ <?=e($e)?></div><?php endforeach; ?>
<div class="pg-header"><div class="pg-title">Admin Users</div><a href="?edit=0" class="adm-btn adm-btn-red">+ Add Admin</a></div>
<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editId?'Edit':'New'?> Admin User</span><a href="<?=SITE_URL?>/admin/pages/admins.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST"><?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Full Name *</label><input class="adm-fi" name="name" value="<?=e($editA['name']??'')?>" required/></div>
        <div class="adm-fg"><label class="adm-fl">Email Address *</label><input class="adm-fi" type="email" name="email" value="<?=e($editA['email']??'')?>" required/></div>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Password <?=$editId?'(leave blank to keep current)':'*'?></label><input class="adm-fi" type="password" name="password" placeholder="Min. 8 characters" <?=!$editId?'required':''/>/></div>
        <div class="adm-fg"><label class="adm-fl">Role</label><select class="adm-fs" name="role"><option value="editor" <?=($editA['role']??'editor')==='editor'?'selected':''?>>Editor</option><option value="super_admin" <?=($editA['role']??'')==='super_admin'?'selected':''?>>Super Admin</option></select></div>
      </div>
      <div class="adm-fg"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editA['is_active']??1)?'checked':''?>> Active</label></div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Create'?> Admin</button>
    </form>
  </div>
</div>
<?php endif; ?>
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Last Login</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach($admins as $a): ?>
  <tr>
    <td><div style="display:flex;align-items:center;gap:10px"><div style="width:36px;height:36px;border-radius:50%;background:<?=$a['id']===$me['id']?'var(--red)':'var(--dark)'?>;display:flex;align-items:center;justify-content:center;font-family:var(--font-h);font-size:13px;font-weight:800;color:#fff;flex-shrink:0"><?=strtoupper(substr($a['name'],0,1))?></div><span style="font-weight:600"><?=e($a['name'])?><?=$a['id']===$me['id']?' <span style="font-size:10px;color:var(--red)">(you)</span>':''?></span></div></td>
    <td style="font-size:13px"><?=e($a['email'])?></td>
    <td><span class="bdg <?=$a['role']==='super_admin'?'bdg-red':'bdg-blue'?>"><?=e($a['role'])?></span></td>
    <td style="font-size:12px;color:var(--muted)"><?=$a['last_login']?timeAgo($a['last_login']):'Never'?></td>
    <td><span class="bdg <?=$a['is_active']?'bdg-green':'bdg-gray'?>"><?=$a['is_active']?'Active':'Inactive'?></span></td>
    <td>
      <div class="act-btns">
        <a href="?edit=<?=$a['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a>
        <?php if($a['id']!==$me['id']): ?>
        <a href="?action=delete&id=<?=$a['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete this admin user?">Del</a>
        <?php endif; ?>
      </div>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
