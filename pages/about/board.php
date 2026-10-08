<?php
define('PAGE_SLUG','board');
require_once __DIR__.'/../../includes/functions.php';
$members = DB::fetchAll("SELECT * FROM board_members WHERE is_active=1 ORDER BY sort_order");
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="#">About Us</a><span>/</span>Board of Directors</div>
    <div class="eyebrow">Leadership</div>
    <h1 class="sec-title" style="color:#fff">Board of Directors</h1>
    <p class="page-hero-desc">Our visionary leadership team guiding MAF Footwear Limited since 2007.</p>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <div class="board-grid">
      <?php foreach($members as $m): ?>
      <div class="board-card fu">
        <div class="board-photo">
          <?php if($m['photo']): ?>
          <img src="<?=mediaUrl($m['photo'])?>" alt="<?=e($m['name'])?>" loading="lazy"/>
          <?php else: ?>
          <div class="board-photo-placeholder">
            <div class="board-initials"><?=strtoupper(substr($m['name'],0,1))?></div>
          </div>
          <?php endif; ?>
        </div>
        <div class="board-body">
          <div class="board-name"><?=e($m['name'])?></div>
          <div class="board-title"><?=e($m['designation'])?></div>
          <?php if($m['bio']): ?>
          <p style="font-size:13px;color:var(--gray);line-height:1.65;margin-top:10px"><?=e($m['bio'])?></p>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Share Capital -->
    <div class="fu" style="margin-top:72px">
      <div class="eyebrow">Corporate Structure</div>
      <h2 class="sec-title" style="font-size:30px;margin-bottom:28px">Share Capital</h2>
      <div style="max-width:700px">
        <table class="cap-table">
          <thead><tr><th>Particulars</th><th>Details</th></tr></thead>
          <tbody>
            <tr><td>Total Authorized Capital</td><td>TK 100,00,00,000</td></tr>
            <tr><td>Total Shares</td><td>1,00,00,000 shares @ TK 100 each</td></tr>
            <tr><td>Shares Taken Up</td><td>17,60,000 shares</td></tr>
            <tr><td>Talha Bin Tayab (Abir) — Chairman</td><td>8,80,000 shares</td></tr>
            <tr><td>Hasnat Md. Abu Obida (Marshall) — MD</td><td>8,80,000 shares</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
