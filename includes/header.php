<?php
if (!defined('PAGE_SLUG')) define('PAGE_SLUG', 'home');
$_meta   = pageMeta(PAGE_SLUG);
$_sname  = setting('site_name', 'MAF Footwear Limited');
$_stag   = setting('site_tagline', '100% Export Oriented Quality Footwear Manufacturing');
$_logo   = setting('site_logo', '');   // uploaded logo filename
$_logoUrl = $_logo ? SITE_URL . '/public/images/' . $_logo : '';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta name="description" content="<?= e($_meta['meta_description'] ?? $_stag) ?>"/>
<meta property="og:title" content="<?= e($_meta['meta_title'] ?? $_sname) ?>"/>
<meta property="og:description" content="<?= e($_meta['meta_description'] ?? $_stag) ?>"/>
<title><?= e($_meta['meta_title'] ?? $_sname) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= SITE_URL ?>/public/css/style.css"/>
</head>
<body>

<!-- ══ NAVIGATION ════════════════════════════════════════════════ -->
<nav class="main-nav" id="mainNav">
  <div class="nav-container">

    <!-- Logo -->
    <a href="<?= SITE_URL ?>/index.php" class="nav-logo">
      <?php if ($_logoUrl): ?>
        <!-- Uploaded logo from dashboard -->
        <img src="<?= e($_logoUrl) ?>"
             alt="<?= e($_sname) ?>"
             style="height:42px;max-width:160px;object-fit:contain; "/>  <!-- filter:brightness(0) invert(1) -->
      <?php else: ?>
        <!-- Default SVG logo -->
        <div class="logo-icon">
          <svg viewBox="0 0 100 62" fill="none" xmlns="http://www.w3.org/2000/svg" width="52" height="32">
            <path d="M5 52 C22 52, 34 10, 54 10 C66 10, 74 30, 96 20"
                  stroke="#CC1E1E" stroke-width="5.5" stroke-linecap="round" fill="none"/>
            <path d="M5 52 C22 52, 34 10, 54 10 C66 10, 74 30, 96 20 L98 34
                     C72 44, 56 28, 36 36 C20 42, 10 56, 5 57 Z"
                  fill="#CC1E1E" opacity="0.12"/>
          </svg>
        </div>
        <div class="logo-text">
          <div class="logo-main"><span>maf</span> Footwear</div>
          <div class="logo-sub">LIMITED</div>
        </div>
      <?php endif; ?>
    </a>

    <!-- Mobile hamburger -->
    <button class="hamburger" id="navToggle" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>

    <!-- Menu -->
    <ul class="nav-menu" id="navMenu">
      <li class="<?= PAGE_SLUG === 'home' ? 'active' : '' ?>">
        <a href="<?= SITE_URL ?>/index.php">Home</a>
      </li>
      <li class="has-dropdown <?= str_starts_with(PAGE_SLUG, 'about') ? 'active' : '' ?>">
        <a href="#">About Us <span class="caret">▾</span></a>
        <ul class="dropdown">
          <li><a href="<?= SITE_URL ?>/pages/about/company-overview.php">Company Overview</a></li>
          <li><a href="<?= SITE_URL ?>/pages/about/company-profile.php">Company Profile</a></li>
          <li><a href="<?= SITE_URL ?>/pages/about/vision.php">Vision, Purpose &amp; Core Values</a></li>
          <li><a href="<?= SITE_URL ?>/pages/about/board.php">Board of Directors</a></li>
          <li><a href="<?= SITE_URL ?>/pages/about/customers.php">Customer</a></li>
        </ul>
      </li>
      <li class="has-dropdown <?= str_starts_with(PAGE_SLUG, 'production') ? 'active' : '' ?>">
        <a href="<?= SITE_URL ?>/pages/production/index.php">Production Units <span class="caret">▾</span></a>
        <ul class="dropdown">
          <?php
          $navProd = DB::fetchAll("SELECT slug,title FROM production_units WHERE is_active=1 ORDER BY sort_order");
          foreach ($navProd as $p):
          ?>
          <li><a href="<?= SITE_URL ?>/pages/production/detail.php?slug=<?= e($p['slug']) ?>"><?= e($p['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </li>
      <li class="has-dropdown <?= str_starts_with(PAGE_SLUG, 'operation') ? 'active' : '' ?>">
        <a href="<?= SITE_URL ?>/pages/operation/index.php">Operation Strength <span class="caret">▾</span></a>
        <ul class="dropdown">
          <?php
          $navOps = DB::fetchAll("SELECT slug,title FROM operation_strength WHERE is_active=1 ORDER BY sort_order");
          foreach ($navOps as $o):
          ?>
          <li><a href="<?= SITE_URL ?>/pages/operation/detail.php?slug=<?= e($o['slug']) ?>"><?= e($o['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </li>
      <li class="<?= PAGE_SLUG === 'products' ? 'active' : '' ?>">
        <a href="<?= SITE_URL ?>/pages/products.php">Products</a>
      </li>
      <li class="has-dropdown <?= str_starts_with(PAGE_SLUG, 'media') ? 'active' : '' ?>">
        <a href="#">Media <span class="caret">▾</span></a>
        <ul class="dropdown">
          <li><a href="<?= SITE_URL ?>/pages/media/news.php">News</a></li>
          <li><a href="<?= SITE_URL ?>/pages/media/gallery.php">Gallery</a></li>
          <li><a href="<?= SITE_URL ?>/pages/media/videos.php">Videos</a></li>
        </ul>
      </li>
      <li class="<?= PAGE_SLUG === 'jobs' ? 'active' : '' ?>">
        <a href="<?= SITE_URL ?>/pages/jobs.php">Jobs</a>
      </li>
      <li class="<?= PAGE_SLUG === 'contact' ? 'active' : '' ?>">
        <a href="<?= SITE_URL ?>/pages/contact.php">Contact</a>
      </li>
    </ul>

  </div>
</nav>
