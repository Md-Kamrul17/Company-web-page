<?php
$pageTitle='Vision & Values';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('Security error');
    $keys=['vision','purpose','core_values','vision_bn','purpose_bn','core_values_bn'];
    foreach($keys as $k){
        $title=clean($_POST[$k.'_title']??'');
        $content=clean($_POST[$k.'_content']??'');
        DB::execute("INSERT INTO vision_values (section_key,section_title,content) VALUES (?,?,?) ON DUPLICATE KEY UPDATE section_title=?,content=?",
            [$k,$title,$content,$title,$content]);
    }
    flash('success','Vision & Values updated successfully.');
    redirect(SITE_URL.'/admin/pages/vision.php');
}

$vv   = DB::fetchAll("SELECT * FROM vision_values");
$vMap = array_column($vv,'content','section_key');
$tMap = array_column($vv,'section_title','section_key');
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Vision, Purpose &amp; Core Values</div></div>
<form method="POST">
  <?=csrfField()?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <!-- English -->
    <div>
      <div style="font-family:var(--font-h);font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:16px;padding:12px 16px;background:var(--red);color:#fff">🇬🇧 English</div>
      <?php foreach(['vision'=>'Vision','purpose'=>'Purpose','core_values'=>'Core Values'] as $k=>$default): ?>
      <div class="panel" style="margin-bottom:16px">
        <div class="panel-head"><span class="panel-title"><?=$default?></span></div>
        <div class="panel-body">
          <div class="adm-fg"><label class="adm-fl">Section Title</label><input class="adm-fi" name="<?=$k?>_title" value="<?=e($tMap[$k]??$default)?>"/></div>
          <div class="adm-fg"><label class="adm-fl">Content</label><textarea class="adm-ft" name="<?=$k?>_content" rows="4"><?=e($vMap[$k]??'')?></textarea></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <!-- Bengali -->
    <div>
      <div style="font-family:var(--font-h);font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:2px;margin-bottom:16px;padding:12px 16px;background:var(--dark);color:#fff">🇧🇩 Bengali (বাংলা)</div>
      <?php foreach(['vision_bn'=>'লক্ষ্য (Vision)','purpose_bn'=>'উদ্দেশ্য (Purpose)','core_values_bn'=>'মূল্যবোধ (Core Values)'] as $k=>$default): ?>
      <div class="panel" style="margin-bottom:16px">
        <div class="panel-head"><span class="panel-title"><?=$default?></span></div>
        <div class="panel-body">
          <div class="adm-fg"><label class="adm-fl">Section Title (Bengali)</label><input class="adm-fi" name="<?=$k?>_title" value="<?=e($tMap[$k]??'')?>"/></div>
          <div class="adm-fg"><label class="adm-fl">Content (Bengali)</label><textarea class="adm-ft" name="<?=$k?>_content" rows="4"><?=e($vMap[$k]??'')?></textarea></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="panel"><div class="panel-body"><button type="submit" class="adm-btn adm-btn-red" style="padding:13px 36px">💾 Save Vision &amp; Values</button></div></div>
</form>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
