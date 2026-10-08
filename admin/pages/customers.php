<?php
$pageTitle='Customers / Clients';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';

if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){
    if(verifyCsrf($_GET['t']??'')){
        $r=DB::fetchOne("SELECT logo FROM customers WHERE id=?",[(int)$_GET['id']]);
        if($r&&$r['logo'])@unlink(UPLOAD_DIR.$r['logo']);
        DB::execute("DELETE FROM customers WHERE id=?",[(int)$_GET['id']]);
        flash('success','Customer deleted.');
    }
    redirect(SITE_URL.'/admin/pages/customers.php');
}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){
    DB::execute("UPDATE customers SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);
    redirect(SITE_URL.'/admin/pages/customers.php');
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $id      = (int)($_POST['id']??0);
    $name    = clean($_POST['name']??'');
    $country = clean($_POST['country']??'');
    $website = clean($_POST['website']??'');
    $sort    = (int)($_POST['sort_order']??0);
    $active  = isset($_POST['is_active'])?1:0;
    $existing= $id?DB::fetchOne("SELECT logo FROM customers WHERE id=?",[$id]):null;
    $logo    = $existing['logo']??null;
    if(!empty($_FILES['logo']['name'])){
        $up=uploadFile($_FILES['logo'],'products');
        if($up){if($logo)@unlink(UPLOAD_DIR.$logo);$logo=$up;}
    }
    if($id){
        DB::execute("UPDATE customers SET name=?,country=?,website=?,logo=?,sort_order=?,is_active=? WHERE id=?",
            [$name,$country,$website,$logo,$sort,$active,$id]);
        flash('success','Customer updated.');
    } else {
        DB::execute("INSERT INTO customers (name,country,website,logo,sort_order,is_active) VALUES (?,?,?,?,?,?)",
            [$name,$country,$website,$logo,$sort,$active]);
        flash('success','Customer added.');
    }
    redirect(SITE_URL.'/admin/pages/customers.php');
}
$editId   = (int)($_GET['edit']??0);
$editC    = $editId?DB::fetchOne("SELECT * FROM customers WHERE id=?",[$editId]):null;
$customers= DB::fetchAll("SELECT * FROM customers ORDER BY sort_order,id");
?>
<?=flashHtml()?>
<div class="pg-header">
  <div class="pg-title">Customers / Brand Partners</div>
  <a href="?edit=0" class="adm-btn adm-btn-red">+ Add Customer</a>
</div>

<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head">
    <span class="panel-title"><?=$editId?'Edit':'Add'?> Customer</span>
    <a href="<?=SITE_URL?>/admin/pages/customers.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a>
  </div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data"><?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Company / Brand Name *</label><input class="adm-fi" name="name" value="<?=e($editC['name']??'')?>" required/></div>
        <div class="adm-fg"><label class="adm-fl">Country</label><input class="adm-fi" name="country" value="<?=e($editC['country']??'')?>" placeholder="e.g. France"/></div>
      </div>
      <div class="adm-fg"><label class="adm-fl">Website URL</label><input class="adm-fi" type="url" name="website" value="<?=e($editC['website']??'')?>" placeholder="https://..."/></div>
      <div class="adm-fg">
        <label class="adm-fl">Brand Logo</label>
        <?php if($editC['logo']??null): ?><img src="<?=mediaUrl($editC['logo'])?>" class="img-preview" style="background:#f5f5f5;padding:8px"/><div style="margin-top:8px"></div><?php endif; ?>
        <input class="adm-fi" type="file" name="logo" accept="image/*"/>
        <p class="adm-hint">PNG with transparent background recommended. Max 2MB.</p>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=$editC['sort_order']??count($customers)+1?>"/></div>
        <div class="adm-fg" style="display:flex;align-items:flex-end;padding-bottom:18px"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editC['is_active']??1)?'checked':''?>> Active (visible on site)</label></div>
      </div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Add'?> Customer</button>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="panel">
  <div class="panel-body np">
    <table class="adm-tbl">
      <thead><tr><th>#</th><th>Logo</th><th>Name</th><th>Country</th><th>Website</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($customers as $c): ?>
      <tr>
        <td style="color:var(--muted);font-weight:700"><?=$c['sort_order']?></td>
        <td>
          <?php if($c['logo']): ?>
          <img src="<?=mediaUrl($c['logo'])?>" style="width:56px;height:36px;object-fit:contain;background:#f5f5f5;padding:4px" alt=""/>
          <?php else: ?>
          <div style="width:56px;height:36px;background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:18px">🤝</div>
          <?php endif; ?>
        </td>
        <td style="font-weight:600"><?=e($c['name'])?></td>
        <td style="font-size:13px"><?=e($c['country']??'—')?></td>
        <td style="font-size:12px;color:var(--muted)"><?php if($c['website']): ?><a href="<?=e($c['website'])?>" target="_blank" style="color:var(--red)">↗ Visit</a><?php else: ?>—<?php endif; ?></td>
        <td><a href="?action=toggle&id=<?=$c['id']?>"><span class="bdg <?=$c['is_active']?'bdg-green':'bdg-red'?>"><?=$c['is_active']?'Active':'Hidden'?></span></a></td>
        <td>
          <div class="act-btns">
            <a href="?edit=<?=$c['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a>
            <a href="?action=delete&id=<?=$c['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete this customer?">Del</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(!$customers): ?>
      <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">No customers yet. <a href="?edit=0" style="color:var(--red)">Add one →</a></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
