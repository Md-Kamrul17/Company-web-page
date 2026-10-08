<?php
$sName    = setting('site_name','MAF Footwear Limited');
$sYear    = setting('established_year','2007');
$fbUrl    = setting('facebook_url');
$ytUrl    = setting('youtube_url');
$liUrl    = setting('linkedin_url');
$webmail  = setting('site_webmail');
$logoFile = setting('site_logo','');
$logoUrl  = $logoFile ? SITE_URL . '/public/images/' . $logoFile : '';
$navProdF = DB::fetchAll("SELECT slug,title FROM production_units WHERE is_active=1 ORDER BY sort_order");
$navOpsF  = DB::fetchAll("SELECT slug,title FROM operation_strength WHERE is_active=1 ORDER BY sort_order");
?>

<!-- ══ FOOTER ════════════════════════════════════════════════════ -->
<footer class="site-footer">
  <div class="footer-main">
    <div class="footer-grid">

      <!-- Col 1: About Us -->
      <div class="footer-col">
        <h4 class="footer-heading">About Us</h4>
        <ul class="footer-links">
          <li><a href="<?=SITE_URL?>/pages/about/company-overview.php">Company Overview</a></li>
          <li><a href="<?=SITE_URL?>/pages/about/company-profile.php">Company Profile</a></li>
          <li><a href="<?=SITE_URL?>/pages/about/vision.php">Vision, Purpose &amp; Core Values</a></li>
          <li><a href="<?=SITE_URL?>/pages/about/board.php">Board of Directors</a></li>
          <li><a href="<?=SITE_URL?>/pages/about/customers.php">Customer</a></li>
        </ul>
      </div>

      <!-- Col 2: Production + Operation -->
      <div class="footer-col">
        <h4 class="footer-heading">Production Units</h4>
        <ul class="footer-links">
          <?php foreach($navProdF as $p): ?>
          <li><a href="<?=SITE_URL?>/pages/production/detail.php?slug=<?=e($p['slug'])?>"><?=e($p['title'])?></a></li>
          <?php endforeach; ?>
        </ul>
        <h4 class="footer-heading" style="margin-top:20px">Operation Strength</h4>
        <ul class="footer-links">
          <?php foreach($navOpsF as $o): ?>
          <li><a href="<?=SITE_URL?>/pages/operation/detail.php?slug=<?=e($o['slug'])?>"><?=e($o['title'])?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Col 3: Others -->
      <div class="footer-col">
        <h4 class="footer-heading">Others</h4>
        <ul class="footer-links">
          <li><a href="<?=SITE_URL?>/pages/products.php">Products</a></li>
          <li><a href="<?=SITE_URL?>/pages/media/news.php">News</a></li>
          <li><a href="<?=SITE_URL?>/pages/media/gallery.php">Gallery</a></li>
          <li><a href="<?=SITE_URL?>/pages/media/videos.php">Videos</a></li>
          <li><a href="<?=SITE_URL?>/pages/jobs.php">Jobs</a></li>
          <li><a href="<?=SITE_URL?>/pages/contact.php">Contact</a></li>
          <?php if($webmail): ?><li><a href="<?=e($webmail)?>" target="_blank">Web Mail</a></li><?php endif; ?>
        </ul>
      </div>

      <!-- Col 4: Contact -->
      <div class="footer-col">
        <h4 class="footer-heading">Contact Info</h4>
        <div class="footer-contact">
          <div class="fc-item"><span class="fc-icon">📍</span><span><?=e(setting('site_address'))?></span></div>
          <div class="fc-item"><span class="fc-icon">📧</span><a href="mailto:<?=e(setting('site_email'))?>"><?=e(setting('site_email'))?></a></div>
          <div class="fc-item"><span class="fc-icon">📞</span>
            <span>
              <?=e(setting('site_phone1'))?><br/>
              <?=e(setting('site_phone2'))?><br/>
              <?=e(setting('site_phone3'))?><br/>
              <?=e(setting('site_phone4'))?>
            </span>
          </div>
          <?php if(setting('site_fax')): ?>
          <div class="fc-item"><span class="fc-icon">📠</span><span><?=e(setting('site_fax'))?></span></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="footer-bottom-inner">
      <span class="footer-copy">© Copyright <?=e($sName)?>. <?=e($sYear)?> – <?=date('Y')?>, All Rights Reserved.</span>
      <div class="footer-bottom-links">
        <a href="<?=SITE_URL?>/pages/contact.php">Contact</a>
        <a href="<?=SITE_URL?>/pages/jobs.php">Jobs</a>
        <?php if($webmail): ?><a href="<?=e($webmail)?>" target="_blank">Web Mail</a><?php endif; ?>
      </div>
    </div>
  </div>
</footer>

<!-- ══ STICKY SOCIAL PANEL ═══════════════════════════════════════ -->
<div class="social-sidebar">
  <a href="<?=SITE_URL?>/index.php" class="social-logo-link">
    <svg viewBox="0 0 100 62" fill="none" width="36" height="22">
      <path d="M5 52 C22 52, 34 10, 54 10 C66 10, 74 30, 96 20" stroke="#CC1E1E" stroke-width="5.5" stroke-linecap="round" fill="none"/>
    </svg>
  </a>
  <span class="social-label">Connect with us</span>
  <div class="social-icons">
    <?php if($fbUrl): ?><a href="<?=e($fbUrl)?>" target="_blank" rel="noopener" class="si si-fb" aria-label="Facebook">f</a><?php endif; ?>
    <?php if($ytUrl): ?><a href="<?=e($ytUrl)?>" target="_blank" rel="noopener" class="si si-yt" aria-label="YouTube">▶</a><?php endif; ?>
    <?php if($liUrl): ?><a href="<?=e($liUrl)?>" target="_blank" rel="noopener" class="si si-li" aria-label="LinkedIn">in</a><?php endif; ?>
  </div>
</div>

<script src="<?=SITE_URL?>/public/js/main.js"></script>
</body>
</html>
