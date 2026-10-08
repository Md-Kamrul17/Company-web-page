<?php
define('PAGE_SLUG','vision');
require_once __DIR__.'/../../includes/functions.php';
$vv = DB::fetchAll("SELECT * FROM vision_values");
$vMap = array_column($vv,'content','section_key');
$tMap = array_column($vv,'section_title','section_key');
require_once __DIR__.'/../../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span><a href="#">About Us</a><span>/</span>Vision, Purpose &amp; Core Values</div>
    <div class="eyebrow">Our Philosophy</div>
    <h1 class="sec-title" style="color:#fff">Vision, Purpose &amp;<br/>Core Values</h1>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <div class="vision-grid">
      <!-- Vision -->
      <div class="vision-card fu">
        <div class="vision-card-title">🎯 <?=e($tMap['vision']??'Vision')?></div>
        <div class="vision-card-text"><?=e($vMap['vision']??'')?></div>
      </div>
      <!-- Purpose -->
      <div class="vision-card fu">
        <div class="vision-card-title">💡 <?=e($tMap['purpose']??'Purpose')?></div>
        <div class="vision-card-text"><?=e($vMap['purpose']??'')?></div>
      </div>
      <!-- Core Values -->
      <div class="vision-card fu">
        <div class="vision-card-title">⭐ <?=e($tMap['core_values']??'Core Values')?></div>
        <div class="vision-card-text">
          <?php
          $vals = explode(',',$vMap['core_values']??'');
          foreach($vals as $v):
          ?>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
            <span style="width:8px;height:8px;border-radius:50%;background:var(--red);flex-shrink:0"></span>
            <span><?=e(trim($v))?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Bilingual section (Bengali) -->
    <div class="vision-bilingual fu" style="margin-top:48px">
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:40px">
        <div>
          <div class="vb-title"><?=e($tMap['vision_bn']??'লক্ষ্য')?></div>
          <div class="vb-text"><?=e($vMap['vision_bn']??'')?></div>
        </div>
        <div>
          <div class="vb-title"><?=e($tMap['purpose_bn']??'উদ্দেশ্য')?></div>
          <div class="vb-text"><?=e($vMap['purpose_bn']??'')?></div>
        </div>
        <div>
          <div class="vb-title"><?=e($tMap['core_values_bn']??'মূল্যবোধ')?></div>
          <div class="vb-text"><?=e($vMap['core_values_bn']??'')?></div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
