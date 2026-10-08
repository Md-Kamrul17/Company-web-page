<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$totalNews  = DB::fetchOne("SELECT COUNT(*) c FROM news_articles WHERE is_published=1")['c'] ?? 0;
$totalJobs  = DB::fetchOne("SELECT COUNT(*) c FROM jobs WHERE is_active=1")['c'] ?? 0;
$totalProds = DB::fetchOne("SELECT COUNT(*) c FROM products WHERE is_active=1")['c'] ?? 0;
$unreadMsgs = DB::fetchOne("SELECT COUNT(*) c FROM contact_submissions WHERE is_read=0")['c'] ?? 0;
$recentNews = DB::fetchAll("SELECT n.*,c.name cat_name FROM news_articles n LEFT JOIN news_categories c ON n.category_id=c.id ORDER BY n.created_at DESC LIMIT 5");
$recentMsgs = DB::fetchAll("SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 6");
?>

<!-- Stat Cards -->
<div class="adm-stat-grid">
  <div class="adm-stat-card asc-red"><div class="asc-icon">📰</div><div class="asc-val"><?=$totalNews?></div><div class="asc-lbl">Published Articles</div></div>
  <div class="adm-stat-card asc-dark"><div class="asc-icon">💼</div><div class="asc-val"><?=$totalJobs?></div><div class="asc-lbl">Active Jobs</div></div>
  <div class="adm-stat-card asc-green"><div class="asc-icon">👟</div><div class="asc-val"><?=$totalProds?></div><div class="asc-lbl">Products</div></div>
  <div class="adm-stat-card asc-blue"><div class="asc-icon">🔔</div><div class="asc-val"><?=$unreadMsgs?></div><div class="asc-lbl">Unread Messages</div></div>
</div>

<!-- Quick Actions -->
<div class="panel" style="margin-bottom:20px">
  <div class="panel-head"><span class="panel-title">Quick Actions</span></div>
  <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
    <a href="<?=SITE_URL?>/admin/pages/news-edit.php" class="adm-btn adm-btn-red">+ New Article</a>
    <a href="<?=SITE_URL?>/admin/pages/jobs.php?edit=0" class="adm-btn adm-btn-out">+ New Job</a>
    <a href="<?=SITE_URL?>/admin/pages/products.php?edit=0" class="adm-btn adm-btn-out">+ New Product</a>
    <a href="<?=SITE_URL?>/admin/pages/hero-slides.php" class="adm-btn adm-btn-out">Hero Slides</a>
    <a href="<?=SITE_URL?>/admin/pages/settings.php" class="adm-btn adm-btn-out">Site Settings</a>
    <a href="<?=SITE_URL?>/admin/pages/contacts.php" class="adm-btn adm-btn-out">
      Messages <?php if($unreadMsgs): ?><span style="background:var(--red);color:#fff;padding:2px 8px;font-size:10px;margin-left:4px"><?=$unreadMsgs?> new</span><?php endif; ?>
    </a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px">
  <!-- Recent Articles -->
  <div class="panel">
    <div class="panel-head">
      <span class="panel-title">Recent Articles</span>
      <a href="<?=SITE_URL?>/admin/pages/news.php" class="adm-btn adm-btn-out adm-btn-sm">All</a>
    </div>
    <div class="panel-body np">
      <table class="adm-tbl">
        <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
        <?php if($recentNews): foreach($recentNews as $a): ?>
        <tr>
          <td><div style="font-weight:600;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?=e($a['title'])?></div></td>
          <td><span class="bdg bdg-gray"><?=e($a['cat_name']??'—')?></span></td>
          <td><span class="bdg <?=$a['is_published']?'bdg-green':'bdg-red'?>"><?=$a['is_published']?'Live':'Draft'?></span></td>
          <td style="font-size:11px;color:var(--muted)"><?=fmtDate($a['created_at'],'M j, Y')?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--muted)">No articles yet.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Messages -->
  <div class="panel">
    <div class="panel-head">
      <span class="panel-title">Recent Messages</span>
      <a href="<?=SITE_URL?>/admin/pages/contacts.php" class="adm-btn adm-btn-out adm-btn-sm">All</a>
    </div>
    <div class="panel-body np">
      <table class="adm-tbl">
        <thead><tr><th>From</th><th>Subject</th><th>Time</th></tr></thead>
        <tbody>
        <?php if($recentMsgs): foreach($recentMsgs as $m): ?>
        <tr style="<?=!$m['is_read']?'background:rgba(204,30,30,.03)':''?>">
          <td>
            <?php if(!$m['is_read']): ?><span style="width:6px;height:6px;border-radius:50%;background:var(--red);display:inline-block;margin-right:6px;vertical-align:middle"></span><?php endif; ?>
            <span style="font-weight:<?=!$m['is_read']?700:400?>"><?=e($m['name'])?></span>
          </td>
          <td style="font-size:12px;color:var(--muted)"><?=e(substr($m['subject']??'General',0,20))?></td>
          <td style="font-size:11px;color:var(--muted);white-space:nowrap"><?=timeAgo($m['created_at'])?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="3" style="text-align:center;padding:32px;color:var(--muted)">No messages.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
