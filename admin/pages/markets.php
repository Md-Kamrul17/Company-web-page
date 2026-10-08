<?php /* markets.php */
$pageTitle='Export Markets';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM export_markets WHERE id=?",[(int)$_GET['id']]);flash('success','Market deleted.');redirect(SITE_URL.'/admin/pages/markets.php');}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){DB::execute("UPDATE export_markets SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);redirect(SITE_URL.'/admin/pages/markets.php');}
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['action']??'')==='add'){
    if(verifyCsrf($_POST['csrf_token']??''))DB::execute("INSERT INTO export_markets (country,flag_emoji,sort_order,is_active) VALUES (?,?,?,1)",[clean($_POST['country']??''),clean($_POST['flag_emoji']??''),(int)($_POST['sort_order']??0)]);
    flash('success','Market added.');redirect(SITE_URL.'/admin/pages/markets.php');
}
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['action']??'')==='update_all'){
    if(verifyCsrf($_POST['csrf_token']??'')){
        foreach(($_POST['markets']??[]) as $mid=>$d){
            DB::execute("UPDATE export_markets SET country=?,flag_emoji=?,sort_order=?,is_active=? WHERE id=?",
                [clean($d['country']),clean($d['flag']),(int)$d['sort'],isset($d['active'])?1:0,(int)$mid]);
        }
        flash('success','Markets updated.');
    }
    redirect(SITE_URL.'/admin/pages/markets.php');
}
$markets=DB::fetchAll("SELECT * FROM export_markets ORDER BY sort_order");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Export Markets</div></div>
<div style="display:grid;grid-template-columns:1fr 320px;gap:20px">
  <form method="POST"><input type="hidden" name="action" value="update_all"><?=csrfField()?>
    <div class="panel"><div class="panel-head"><span class="panel-title">Current Markets</span></div>
      <div class="panel-body np"><table class="adm-tbl">
        <thead><tr><th>Country</th><th>Flag</th><th>Order</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach($markets as $m): ?>
        <tr>
          <td><input class="adm-fi" name="markets[<?=$m['id']?>][country]" value="<?=e($m['country'])?>"/></td>
          <td><input class="adm-fi" name="markets[<?=$m['id']?>][flag]" value="<?=e($m['flag_emoji'])?>" style="width:70px"/></td>
          <td><input class="adm-fi" type="number" name="markets[<?=$m['id']?>][sort]" value="<?=$m['sort_order']?>" style="width:60px"/></td>
          <td><input type="checkbox" name="markets[<?=$m['id']?>][active]" <?=$m['is_active']?'checked':''?>></td>
          <td><a href="?action=delete&id=<?=$m['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete?">✕</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody></table>
      </div>
      <div style="padding:14px 22px"><button type="submit" class="adm-btn adm-btn-red">💾 Save All</button></div>
    </div>
  </form>
  <form method="POST"><input type="hidden" name="action" value="add"><?=csrfField()?>
    <div class="panel" style="height:fit-content"><div class="panel-head"><span class="panel-title">Add Market</span></div>
      <div class="panel-body">
        <div class="adm-fg"><label class="adm-fl">Country Name *</label><input class="adm-fi" name="country" placeholder="Germany" required/></div>
        <div class="adm-fg"><label class="adm-fl">Flag Emoji</label><input class="adm-fi" name="flag_emoji" placeholder="🇩🇪"/><p class="adm-hint">Copy flag emoji from emojipedia.org</p></div>
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=count($markets)+1?>"/></div>
        <button type="submit" class="adm-btn adm-btn-red" style="width:100%">+ Add Market</button>
      </div>
    </div>
  </form>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
