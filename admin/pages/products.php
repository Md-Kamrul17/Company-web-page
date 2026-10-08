<?php
$pageTitle='Products';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';

// delete
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){
    if(verifyCsrf($_GET['t']??'')){
        $r=DB::fetchOne("SELECT image FROM products WHERE id=?",[(int)$_GET['id']]);
        if($r&&$r['image']) @unlink(UPLOAD_DIR.$r['image']);
        DB::execute("DELETE FROM products WHERE id=?",[(int)$_GET['id']]);
        flash('success','Product deleted.');
    }
    redirect(SITE_URL.'/admin/pages/products.php');
}
// toggle
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){
    DB::execute("UPDATE products SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);
    redirect(SITE_URL.'/admin/pages/products.php');
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??'')) die('err');
    $id     = (int)($_POST['id']??0);
    $catId  = (int)($_POST['category_id']??0)?:null;
    $title  = clean($_POST['title']??'');
    $desc   = clean($_POST['description']??'');
    $sort   = (int)($_POST['sort_order']??0);
    $active = isset($_POST['is_active'])?1:0;
    $existing = $id ? DB::fetchOne("SELECT image FROM products WHERE id=?",[$id]) : null;
    $img = $existing['image']??null;
    if(!empty($_FILES['image']['name'])){
        $up=uploadFile($_FILES['image'],'products'); if($up){ if($img)@unlink(UPLOAD_DIR.$img); $img=$up; }
    }
    if(!$img&&!$id){ flash('error','Product image is required.'); redirect(SITE_URL.'/admin/pages/products.php?edit=0'); }
    if($id){
        DB::execute("UPDATE products SET category_id=?,title=?,description=?,image=?,sort_order=?,is_active=? WHERE id=?",[$catId,$title,$desc,$img,$sort,$active,$id]);
        flash('success','Product updated.');
    } else {
        DB::execute("INSERT INTO products (category_id,title,description,image,sort_order,is_active) VALUES (?,?,?,?,?,?)",[$catId,$title,$desc,$img,$sort,$active]);
        flash('success','Product added.');
    }
    redirect(SITE_URL.'/admin/pages/products.php');
}

$editId  = isset($_GET['edit'])?(int)$_GET['edit']:null;
$editPrd = ($editId!==null&&$editId>0) ? DB::fetchOne("SELECT * FROM products WHERE id=?",[$editId]) : null;
$cats    = DB::fetchAll("SELECT * FROM product_categories WHERE slug!='all' ORDER BY sort_order");
$products= DB::fetchAll("SELECT p.*,c.name cat_name FROM products p LEFT JOIN product_categories c ON p.category_id=c.id ORDER BY p.sort_order,p.id");
?>
<?=flashHtml()?>
<div class="pg-header">
  <div class="pg-title">Products <span style="font-size:13px;font-weight:400;color:var(--muted)">(<?=count($products)?>)</span></div>
  <a href="?edit=0" class="adm-btn adm-btn-red">+ Add Product</a>
</div>

<?php if($editId!==null): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editId?'Edit':'Add'?> Product</span><a href="<?=SITE_URL?>/admin/pages/products.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data">
      <?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Product Title (optional)</label><input class="adm-fi" name="title" value="<?=e($editPrd['title']??'')?>"/></div>
        <div class="adm-fg"><label class="adm-fl">Category</label>
          <select class="adm-fs" name="category_id">
            <option value="">— Select Category —</option>
            <?php foreach($cats as $c): ?>
            <option value="<?=$c['id']?>" <?=($editPrd['category_id']??'')==$c['id']?'selected':''?>><?=e($c['name'])?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="adm-fg">
        <label class="adm-fl">Product Image <?=$editId?'(leave blank to keep current)':'*'?></label>
        <?php if($editPrd['image']??null): ?><img src="<?=mediaUrl($editPrd['image'])?>" class="img-preview"/><div style="margin-top:8px"></div><?php endif; ?>
        <input class="adm-fi" type="file" name="image" accept="image/*" <?=!$editId?'required':''?>/>
        <p class="adm-hint">JPEG/PNG/WebP. Recommended 600×600px (square) for best display.</p>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=$editPrd['sort_order']??count($products)+1?>"/></div>
        <div class="adm-fg" style="display:flex;align-items:flex-end;padding-bottom:18px"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editPrd['is_active']??1)?'checked':''?>> Active</label></div>
      </div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Add'?> Product</button>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="panel">
  <div class="panel-body np">
    <table class="adm-tbl">
      <thead><tr><th>#</th><th>Image</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach($products as $p): ?>
      <tr>
        <td style="color:var(--muted);font-family:var(--font-h);font-weight:700"><?=$p['sort_order']?></td>
        <td><img src="<?=mediaUrl($p['image'])?>" class="tbl-img" alt="" onerror="this.src='https://placehold.co/56x40/F5F5F5/999?text=?'"/></td>
        <td style="font-weight:500"><?=e($p['title']?:'—')?></td>
        <td><span class="bdg bdg-gray"><?=e($p['cat_name']??'—')?></span></td>
        <td><a href="?action=toggle&id=<?=$p['id']?>"><span class="bdg <?=$p['is_active']?'bdg-green':'bdg-red'?>"><?=$p['is_active']?'Active':'Hidden'?></span></a></td>
        <td>
          <div class="act-btns">
            <a href="?edit=<?=$p['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a>
            <a href="?action=delete&id=<?=$p['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete this product?">Delete</a>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if(!$products): ?><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No products yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
