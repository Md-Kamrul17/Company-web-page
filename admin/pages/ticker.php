<?php /* ticker.php */
$pageTitle='Ticker Items';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM ticker_items WHERE id=?",[(int)$_GET['id']]);flash('success','Item deleted.');redirect(SITE_URL.'/admin/pages/ticker.php');}
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['action']??'')==='add'){if(verifyCsrf($_POST['csrf_token']??''))DB::execute("INSERT INTO ticker_items (text,sort_order,is_active) VALUES (?,?,1)",[clean($_POST['text']??''),(int)($_POST['sort_order']??0)]);flash('success','Item added.');redirect(SITE_URL.'/admin/pages/ticker.php');}
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['action']??'')==='save'){
    if(verifyCsrf($_POST['csrf_token']??'')){
        foreach(($_POST['items']??[]) as $tid=>$d) DB::execute("UPDATE ticker_items SET text=?,sort_order=?,is_active=? WHERE id=?",[clean($d['text']??''),(int)$d['sort'],isset($d['active'])?1:0,(int)$tid]);
        flash('success','Ticker updated.');
    }
    redirect(SITE_URL.'/admin/pages/ticker.php');
}
$items=DB::fetchAll("SELECT * FROM ticker_items ORDER BY sort_order");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Ticker Items</div></div>
<div style="display:grid;grid-template-columns:1fr 300px;gap:20px">
  <form method="POST"><input type="hidden" name="action" value="save"><?=csrfField()?>
    <div class="panel"><div class="panel-head"><span class="panel-title">Current Items (scrolling ticker)</span></div>
      <div class="panel-body np"><table class="adm-tbl">
        <thead><tr><th>Text</th><th>Order</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach($items as $it): ?>
        <tr><td><input class="adm-fi" name="items[<?=$it['id']?>][text]" value="<?=e($it['text'])?>"/></td>
          <td><input class="adm-fi" type="number" name="items[<?=$it['id']?>][sort]" value="<?=$it['sort_order']?>" style="width:60px"/></td>
          <td><input type="checkbox" name="items[<?=$it['id']?>][active]" <?=$it['is_active']?'checked':''?>></td>
          <td><a href="?action=delete&id=<?=$it['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete?">✕</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody></table>
      </div>
      <div style="padding:14px 22px"><button type="submit" class="adm-btn adm-btn-red">💾 Save</button></div>
    </div>
  </form>
  <form method="POST"><input type="hidden" name="action" value="add"><?=csrfField()?>
    <div class="panel" style="height:fit-content"><div class="panel-head"><span class="panel-title">Add Item</span></div>
      <div class="panel-body">
        <div class="adm-fg"><label class="adm-fl">Ticker Text *</label><input class="adm-fi" name="text" required/></div>
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=count($items)+1?>"/></div>
        <button type="submit" class="adm-btn adm-btn-red" style="width:100%">+ Add</button>
      </div>
    </div>
  </form>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
