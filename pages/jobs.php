<?php
define('PAGE_SLUG','jobs');
require_once __DIR__.'/../includes/functions.php';
$dept = clean($_GET['dept']??'');
$where='is_active=1'; $params=[];
if($dept){ $where.=' AND department=?'; $params[]=$dept; }
$jobs  = DB::fetchAll("SELECT * FROM jobs WHERE $where ORDER BY created_at DESC",$params);
$depts = DB::fetchAll("SELECT DISTINCT department FROM jobs WHERE is_active=1 ORDER BY department");
require_once __DIR__.'/../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span>Jobs</div>
    <div class="eyebrow">Careers</div>
    <h1 class="sec-title" style="color:#fff">Job Openings</h1>
    <p class="page-hero-desc">Join our team of 5,000+ skilled professionals. We are always looking for talented, passionate people.</p>
  </div>
</section>

<!-- Why join -->
<section style="background:var(--dark);padding:64px 0">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px">
      <?php $perks=[['🏆','Industry Leader','Work with Bangladesh\'s leading shoe exporter'],['🌍','Global Exposure','Serve brands across 20+ countries'],['📈','Career Growth','Clear career paths and continuous training'],['🤝','Great Culture','Inclusive, respectful workplace since 2007']];
      foreach($perks as [$ico,$ttl,$dsc]): ?>
      <div class="fu" style="text-align:center;padding:28px 20px;border-top:3px solid var(--red)">
        <div style="font-size:32px;margin-bottom:12px"><?=$ico?></div>
        <div style="font-family:var(--font-h);font-size:12px;font-weight:800;text-transform:uppercase;color:#fff;margin-bottom:8px"><?=$ttl?></div>
        <div style="font-size:13px;color:rgba(255,255,255,.45);line-height:1.6"><?=$dsc?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <!-- Filters + search -->
    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:36px;flex-wrap:wrap;gap:20px">
      <div>
        <div class="eyebrow">Open Positions</div>
        <h2 class="sec-title" style="font-size:30px"><?=count($jobs)?> Position<?=count($jobs)!==1?'s':''?> Available</h2>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
        <a href="<?=SITE_URL?>/pages/jobs.php" class="pf-btn <?=!$dept?'active':''?>">All</a>
        <?php foreach($depts as $d): ?>
        <a href="?dept=<?=urlencode($d['department'])?>" class="pf-btn <?=$dept===$d['department']?'active':''?>"><?=e($d['department'])?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Keyword search -->
    <div class="job-search fu">
      <input type="text" id="jobSearch" placeholder="Search by keyword, title or department..." class="fi"/>
    </div>

    <?php if($jobs): ?>
    <?php foreach($jobs as $j): ?>
    <div class="job-card fu">
      <div class="job-header">
        <h3 class="job-title"><?=e($j['title'])?></h3>
        <span class="job-tag"><?=e($j['department'])?></span>
      </div>
      <div class="job-meta">
        <span class="jm">📍 <?=e($j['location'])?></span>
        <span class="jm">⏱ <?=e($j['job_type'])?></span>
        <?php if($j['deadline']): ?>
        <span class="jm" style="color:var(--red)">⏰ Deadline: <?=fmtDate($j['deadline'],'M j, Y')?></span>
        <?php endif; ?>
      </div>
      <?php if($j['description']): ?>
      <div class="job-desc"><?=e($j['description'])?></div>
      <?php endif; ?>
      <?php if($j['requirements']): ?>
      <p style="font-size:13px;color:var(--dark);font-weight:500;margin-bottom:18px"><strong>Requirements:</strong> <?=e($j['requirements'])?></p>
      <?php endif; ?>
      <?php if($j['responsibilities']): ?>
      <p style="font-size:13px;color:var(--dark);font-weight:500;margin-bottom:18px"><strong>Responsibilities:</strong> <?=e($j['responsibilities'])?></p>
      <?php endif; ?>
      <a href="<?=SITE_URL?>/pages/contact.php?subject=Application: <?=urlencode($j['title'])?>" class="btn btn-red" style="font-size:11px;padding:11px 24px">Apply Now →</a>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div style="text-align:center;padding:80px;background:var(--gray-bg)">
      <p style="font-family:var(--font-h);font-size:14px;color:var(--gray)">No open positions at this time. Please check back soon.</p>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
