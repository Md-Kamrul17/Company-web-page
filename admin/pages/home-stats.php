<?php /* home-stats.php */
$pageTitle='Stats / Counters';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM home_stats WHERE id=?",[(int)$_GET['id']]);flash('success','Stat deleted.');redirect(SITE_URL.'/admin/pages/home-stats.php');}
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['action']??'')==='add'){
    if(verifyCsrf($_POST['csrf_token']??''))DB::execute("INSERT INTO home_stats (value,suffix,label,sort_order,is_active) VALUES (?,?,?,?,1)",[clean($_POST['value']??''),clean($_POST['suffix']??''),clean($_POST['label']??''),(int)($_POST['sort_order']??0)]);
    flash('success','Stat added.');redirect(SITE_URL.'/admin/pages/home-stats.php');
}
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['action']??'')==='update_all'){
    if(verifyCsrf($_POST['csrf_token']??'')){
        foreach(($_POST['stats']??[]) as $sid=>$d){
            DB::execute("UPDATE home_stats SET value=?,suffix=?,label=?,sort_order=?,is_active=? WHERE id=?",
                [clean($d['value']),clean($d['suffix']),clean($d['label']),(int)$d['sort'],isset($d['active'])?1:0,(int)$sid]);
        }
        flash('success','Stats updated.');
    }
    redirect(SITE_URL.'/admin/pages/home-stats.php');
}
$stats=DB::fetchAll("SELECT * FROM home_stats ORDER BY sort_order");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Stats / Counters</div></div>
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">
  <form method="POST"><?=csrfField()?><input type="hidden" name="action" value="update_all"/>
    <div class="panel"><div class="panel-head"><span class="panel-title">Current Stats</span></div>
      <div class="panel-body np"><table class="adm-tbl">
        <thead><tr><th>Value</th><th>Suffix</th><th>Label</th><th>Order</th><th>Active</th><th></th></tr></thead>
        <tbody>
        <?php foreach($stats as $s): ?>
        <tr>
          <td><input class="adm-fi" name="stats[<?=$s['id']?>][value]" value="<?=e($s['value'])?>" style="width:80px"/></td>
          <td><input class="adm-fi" name="stats[<?=$s['id']?>][suffix]" value="<?=e($s['suffix'])?>" style="width:60px"/></td>
          <td><input class="adm-fi" name="stats[<?=$s['id']?>][label]" value="<?=e($s['label'])?>"/></td>
          <td><input class="adm-fi" type="number" name="stats[<?=$s['id']?>][sort]" value="<?=$s['sort_order']?>" style="width:60px"/></td>
          <td><input type="checkbox" name="stats[<?=$s['id']?>][active]" <?=$s['is_active']?'checked':''?>></td>
          <td><a href="?action=delete&id=<?=$s['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete?">✕</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody></table>
      </div>
      <div style="padding:14px 22px"><button type="submit" class="adm-btn adm-btn-red">💾 Save All</button></div>
    </div>
  </form>
  <form method="POST"><?=csrfField()?><input type="hidden" name="action" value="add"/>
    <div class="panel" style="height:fit-content"><div class="panel-head"><span class="panel-title">Add New Stat</span></div>
      <div class="panel-body">
        <div class="adm-fg"><label class="adm-fl">Value *</label><input class="adm-fi" name="value" placeholder="5000" required/></div>
        <div class="adm-fg"><label class="adm-fl">Suffix</label><input class="adm-fi" name="suffix" placeholder="+"/></div>
        <div class="adm-fg"><label class="adm-fl">Label *</label><input class="adm-fi" name="label" placeholder="Workforce" required/></div>
        <div class="adm-fg"><label class="adm-fl">Sort Order</label><input class="adm-fi" type="number" name="sort_order" value="<?=count($stats)+1?>"/></div>
        <button type="submit" class="adm-btn adm-btn-red" style="width:100%">+ Add Stat</button>
      </div>
    </div>
  </form>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
