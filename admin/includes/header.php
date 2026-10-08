<?php
ob_start();
requireAdmin();
$adm    = currentAdmin();
$unread = (int)(DB::fetchOne("SELECT COUNT(*) c FROM contact_submissions WHERE is_read=0")['c']??0);
$cur    = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title><?=isset($pageTitle)?e($pageTitle).' — ':''?>MAF Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@400;500&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?=SITE_URL?>/admin/css/admin.css"/>
</head>
<body>
<div class="adm-layout">

<!-- SIDEBAR  -->
<aside class="adm-sidebar" id="admSidebar">
  <div class="sb-logo">
    <svg width="38" height="24" viewBox="0 0 90 58" fill="none">
      <path d="M4 48 C20 48,30 12,48 12 C58 12,64 28,86 20" stroke="#c20606" stroke-width="5" stroke-linecap="round" fill="none"/></svg>
    <div><div class="sb-brand"><span>maf</span> Footwear</div><div class="sb-brand-sub">Admin Panel</div></div>
  </div>  



  <nav class="sb-nav">
    <div class="sb-section">Overview</div>
    <a href="<?=SITE_URL?>/admin/dashboard.php" class="sb-link <?=$cur==='dashboard.php'?'active':''?>"><span class="si">📊</span> Dashboard</a>

    <div class="sb-section">Homepage</div>
    <a href="<?=SITE_URL?>/admin/pages/hero-slides.php" class="sb-link <?=$cur==='hero-slides.php'?'active':''?>"><span class="si">🎯</span> Hero Slides</a>
    <a href="<?=SITE_URL?>/admin/pages/home-stats.php" class="sb-link <?=$cur==='home-stats.php'?'active':''?>"><span class="si">📈</span> Stats / Counters</a>
    <a href="<?=SITE_URL?>/admin/pages/home-intro.php" class="sb-link <?=$cur==='home-intro.php'?'active':''?>"><span class="si">📝</span> Intro Text</a>
    <a href="<?=SITE_URL?>/admin/pages/ticker.php" class="sb-link <?=$cur==='ticker.php'?'active':''?>"><span class="si">📢</span> Ticker Items</a>

    <div class="sb-section">About Us</div>
    <a href="<?=SITE_URL?>/admin/pages/company-pages.php" class="sb-link <?=$cur==='company-pages.php'?'active':''?>"><span class="si">🏢</span> Company Pages</a>
    <a href="<?=SITE_URL?>/admin/pages/vision.php" class="sb-link <?=$cur==='vision.php'?'active':''?>"><span class="si">🎯</span> Vision &amp; Values</a>
    <a href="<?=SITE_URL?>/admin/pages/board.php" class="sb-link <?=$cur==='board.php'?'active':''?>"><span class="si">👥</span> Board of Directors</a>
    <a href="<?=SITE_URL?>/admin/pages/customers.php" class="sb-link <?=$cur==='customers.php'?'active':''?>"><span class="si">🤝</span> Customers</a>

    <div class="sb-section">Content</div>
    <a href="<?=SITE_URL?>/admin/pages/production.php" class="sb-link <?=in_array($cur,['production.php','production-edit.php'])?'active':''?>"><span class="si">🏭</span> Production Units</a>
    <a href="<?=SITE_URL?>/admin/pages/operation.php" class="sb-link <?=in_array($cur,['operation.php','operation-edit.php'])?'active':''?>"><span class="si">⚙️</span> Operation Strength</a>
    <a href="<?=SITE_URL?>/admin/pages/products.php" class="sb-link <?=in_array($cur,['products.php','products-edit.php'])?'active':''?>"><span class="si">👟</span> Products</a>
    <a href="<?=SITE_URL?>/admin/pages/markets.php" class="sb-link <?=$cur==='markets.php'?'active':''?>"><span class="si">🌍</span> Export Markets</a>

    <div class="sb-section">Media</div>
    <a href="<?=SITE_URL?>/admin/pages/news.php" class="sb-link <?=in_array($cur,['news.php','news-edit.php'])?'active':''?>"><span class="si">📰</span> News Articles</a>
    <a href="<?=SITE_URL?>/admin/pages/gallery.php" class="sb-link <?=in_array($cur,['gallery.php','gallery-images.php'])?'active':''?>"><span class="si">🖼️</span> Gallery Albums</a>
    <a href="<?=SITE_URL?>/admin/pages/videos.php" class="sb-link <?=in_array($cur,['videos.php','videos-edit.php'])?'active':''?>"><span class="si">🎬</span> Videos</a>

    <div class="sb-section">Recruitment</div>
    <a href="<?=SITE_URL?>/admin/pages/jobs.php" class="sb-link <?=in_array($cur,['jobs.php','jobs-edit.php'])?'active':''?>"><span class="si">💼</span> Job Listings</a>

    <div class="sb-section">Enquiries</div>
    <a href="<?=SITE_URL?>/admin/pages/contacts.php" class="sb-link <?=$cur==='contacts.php'?'active':''?>">
      <span class="si">✉️</span> Messages
      <?php if($unread>0): ?><span class="sb-badge"><?=$unread?></span><?php endif; ?>
    </a>

    <div class="sb-section">System</div>
    <a href="<?=SITE_URL?>/admin/pages/settings.php" class="sb-link <?=$cur==='settings.php'?'active':''?>"><span class="si">⚙️</span> Site Settings</a>
    <a href="<?=SITE_URL?>/admin/pages/page-meta.php" class="sb-link <?=$cur==='page-meta.php'?'active':''?>"><span class="si">🔍</span> Page SEO</a>
    <a href="<?=SITE_URL?>/admin/pages/admins.php" class="sb-link <?=$cur==='admins.php'?'active':''?>"><span class="si">🔐</span> Admin Users</a>
  </nav>
  <div class="sb-footer">
    <div class="sb-user">
      <div class="sb-avatar"><?=strtoupper(substr($adm['name'],0,1))?></div>
      <div><div class="sb-uname"><?=e($adm['name'])?></div><div class="sb-urole"><?=e($adm['role'])?></div></div>
    </div>
    <a href="<?=SITE_URL?>/admin/logout.php" class="sb-logout">⬅ Sign Out</a>
  </div>
</aside>

<!-- MAIN -->
<div class="adm-main">
  <header class="adm-topbar">
    <div class="adm-topbar-title"><?=isset($pageTitle)?'<span>'.e($pageTitle).'</span>':'Dashboard'?></div>
    <div class="adm-topbar-actions">
      <a href="<?=SITE_URL?>" target="_blank" class="adm-site-link">🌐 View Site</a>
    </div>
  </header>
  <div class="adm-content">
