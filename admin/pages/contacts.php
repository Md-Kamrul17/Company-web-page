<?php
$pageTitle='Contact Messages';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if(($_GET['action']??'')==='delete'&&isset($_GET['id'])){
    if(verifyCsrf($_GET['t']??''))DB::execute("DELETE FROM contact_submissions WHERE id=?",[(int)$_GET['id']]);
    flash('success','Message deleted.');redirect(SITE_URL.'/admin/pages/contacts.php');
}
$view=null;
if(isset($_GET['id'])&&!isset($_GET['action'])){
    $view=DB::fetchOne("SELECT * FROM contact_submissions WHERE id=?",[(int)$_GET['id']]);
    if($view)DB::execute("UPDATE contact_submissions SET is_read=1 WHERE id=?",[(int)$_GET['id']]);
}
$msgs=DB::fetchAll("SELECT * FROM contact_submissions ORDER BY created_at DESC");
?>
<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Contact Messages</div></div>
<?php if($view): ?>
<div class="panel" style="margin-bottom:24px">
  <div class="panel-head"><span class="panel-title">Message from <?=e($view['name'])?></span><a href="<?=SITE_URL?>/admin/pages/contacts.php" class="adm-btn adm-btn-out adm-btn-sm">← Back</a></div>
  <div class="panel-body">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:24px">
      <?php foreach(['Name'=>$view['name'],'Email'=>$view['email'],'Phone'=>($view['phone']?:'—'),'Subject'=>($view['subject']?:'General')] as $l=>$v): ?>
      <div><div style="font-family:var(--font-h);font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted);margin-bottom:4px"><?=$l?></div><div style="font-size:14px;font-weight:500"><?=e($v)?></div></div>
      <?php endforeach; ?>
    </div>
    <div style="background:var(--bg);padding:20px;border-left:4px solid var(--red);font-size:14px;line-height:1.75;color:var(--text)"><?=nl2br(e($view['message']))?></div>
    <div style="margin-top:14px;font-size:12px;color:var(--muted)">Received: <?=fmtDate($view['created_at'],'F j, Y \a\t g:i A')?></div>
    <div style="margin-top:18px;display:flex;gap:12px">
      <a href="mailto:<?=e($view['email'])?>?subject=Re: <?=urlencode($view['subject']??'Your Enquiry')?>" class="adm-btn adm-btn-red">Reply via Email</a>
      <a href="?action=delete&id=<?=$view['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del" data-confirm="Delete this message?">Delete</a>
    </div>
  </div>
</div>
<?php endif; ?>
<div class="panel"><div class="panel-body np">
  <table class="adm-tbl">
    <thead><tr><th>From</th><th>Subject</th><th>Preview</th><th>Time</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($msgs as $m): ?>
    <tr style="<?=!$m['is_read']?'background:rgba(204,30,30,.03)':''?>">
      <td>
        <?php if(!$m['is_read']): ?><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--red);margin-right:7px;vertical-align:middle"></span><?php endif; ?>
        <span style="font-weight:<?=!$m['is_read']?'700':'400'?>"><?=e($m['name'])?></span><br/>
        <span style="font-size:11px;color:var(--muted)"><?=e($m['email'])?></span>
      </td>
      <td><span class="bdg bdg-gray"><?=e(substr($m['subject']??'General',0,24))?></span></td>
      <td style="font-size:12px;color:var(--muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?=e(substr($m['message'],0,80))?>…</td>
      <td style="font-size:11px;color:var(--muted);white-space:nowrap"><?=timeAgo($m['created_at'])?></td>
      <td>
        <div class="act-btns">
          <a href="?id=<?=$m['id']?>" class="adm-btn adm-btn-out adm-btn-sm">View</a>
          <a href="?action=delete&id=<?=$m['id']?>&t=<?=csrfToken()?>" class="adm-btn adm-btn-del adm-btn-sm" data-confirm="Delete message?">Del</a>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if(!$msgs): ?><tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No messages yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div></div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
