<?php /* jobs.php */
$pageTitle='Job Listings';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM jobs WHERE id=?",[(int)$_GET['id']]);flash('success','Job deleted.');redirect(SITE_URL.'/admin/pages/jobs.php');}
if(($_GET['action']??'')==='toggle'&&isset($_GET['id'])){DB::execute("UPDATE jobs SET is_active=NOT is_active WHERE id=?",[(int)$_GET['id']]);redirect(SITE_URL.'/admin/pages/jobs.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $id=$_POST['id']??0;$title=clean($_POST['title']??'');$dept=clean($_POST['department']??'');$loc=clean($_POST['location']??'');$type=clean($_POST['job_type']??'Full-time');$desc=clean($_POST['description']??'');$req=clean($_POST['requirements']??'');$resp=clean($_POST['responsibilities']??'');$dead=clean($_POST['deadline']??'')?:null;$active=isset($_POST['is_active'])?1:0;
    if($id){DB::execute("UPDATE jobs SET title=?,department=?,location=?,job_type=?,description=?,requirements=?,responsibilities=?,deadline=?,is_active=? WHERE id=?",[$title,$dept,$loc,$type,$desc,$req,$resp,$dead,$active,$id]);flash('success','Job updated.');}
    else{DB::execute("INSERT INTO jobs (title,department,location,job_type,description,requirements,responsibilities,deadline,is_active) VALUES (?,?,?,?,?,?,?,?,?)",[$title,$dept,$loc,$type,$desc,$req,$resp,$dead,$active]);flash('success','Job created.');}
    redirect(SITE_URL.'/admin/pages/jobs.php');
}
$editId=(int)($_GET['edit']??0);$editJob=$editId?DB::fetchOne("SELECT * FROM jobs WHERE id=?",[$editId]):null;
$jobs=DB::fetchAll("SELECT * FROM jobs ORDER BY created_at DESC");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Job Listings</div><a href="?edit=0" class="adm-btn adm-btn-red">+ Add Job</a></div>
<?php if(isset($_GET['edit'])): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title"><?=$editId?'Edit':'Add'?> Job</span><a href="<?=SITE_URL?>/admin/pages/jobs.php" class="adm-btn adm-btn-out adm-btn-sm">Cancel</a></div>
  <div class="panel-body">
    <form method="POST"><?=csrfField()?><input type="hidden" name="id" value="<?=$editId?>"/>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Job Title *</label><input class="adm-fi" name="title" value="<?=e($editJob['title']??'')?>" required/></div>
        <div class="adm-fg"><label class="adm-fl">Department</label><input class="adm-fi" name="department" value="<?=e($editJob['department']??'')?>" placeholder="e.g. Manufacturing"/></div>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Location</label><input class="adm-fi" name="location" value="<?=e($editJob['location']??'Chittagong, Bangladesh')?>"/></div>
        <div class="adm-fg"><label class="adm-fl">Job Type</label><select class="adm-fs" name="job_type"><?php foreach(['Full-time','Part-time','Contract'] as $t): ?><option <?=($editJob['job_type']??'Full-time')===$t?'selected':''?>><?=$t?></option><?php endforeach; ?></select></div>
      </div>
      <div class="adm-row">
        <div class="adm-fg"><label class="adm-fl">Application Deadline</label><input class="adm-fi" type="date" name="deadline" value="<?=e($editJob['deadline']??'')?>"/></div>
        <div class="adm-fg" style="display:flex;align-items:flex-end;padding-bottom:18px"><label class="adm-check"><input type="checkbox" name="is_active" <?=($editJob['is_active']??1)?'checked':''?>> Active</label></div>
      </div>
      <div class="adm-fg"><label class="adm-fl">Job Description</label><textarea class="adm-ft" name="description" rows="4"><?=e($editJob['description']??'')?></textarea></div>
      <div class="adm-fg"><label class="adm-fl">Requirements</label><textarea class="adm-ft" name="requirements" rows="4"><?=e($editJob['requirements']??'')?></textarea></div>
      <div class="adm-fg"><label class="adm-fl">Responsibilities</label><textarea class="adm-ft" name="responsibilities" rows="4"><?=e($editJob['responsibilities']??'')?></textarea></div>
      <button type="submit" class="adm-btn adm-btn-red">💾 <?=$editId?'Update':'Create'?> Job</button>
    </form>
  </div>
</div>
<?php endif; ?>
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl"><thead><tr><th>Title</th><th>Dept</th><th>Location</th><th>Type</th><th>Deadline</th><th>Status</th><th>Actions</th></tr></thead><tbody>
  <?php foreach($jobs as $j): ?>
  <tr>
    <td style="font-weight:600"><?=e($j['title'])?></td>
    <td><?=e($j['department'])?></td>
    <td style="font-size:12px"><?=e($j['location'])?></td>
    <td><span class="bdg bdg-gray"><?=e($j['job_type'])?></span></td>
    <td style="font-size:12px;color:var(--muted)"><?=$j['deadline']?fmtDate($j['deadline'],'M j, Y'):'—'?></td>
    <td><a href="?action=toggle&id=<?=$j['id']?>"><span class="bdg <?=$j['is_active']?'bdg-green':'bdg-red'?>"><?=$j['is_active']?'Active':'Inactive'?></span></a></td>
    <td><div class="act-btns"><a href="?edit=<?=$j['id']?>" class="adm-btn adm-btn-out adm-btn-sm">Edit</a><a href="?action=delete&id=<?=$j['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete job?">Del</a></div></td>
  </tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
