<?php
define('PAGE_SLUG','contact');
require_once __DIR__.'/../includes/functions.php';
$success=false; $errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??'')){ $errors[]='Security error. Please try again.'; }
    else{
        $name    = clean($_POST['name']??'');
        $email   = clean($_POST['email']??'');
        $phone   = clean($_POST['phone']??'');
        $subject = clean($_POST['subject']??'');
        $message = clean($_POST['message']??'');
        if(!$name)  $errors[]='Name is required.';
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Valid email is required.';
        if(!$message) $errors[]='Message is required.';
        if(!$errors){
            DB::execute("INSERT INTO contact_submissions (name,email,phone,subject,message) VALUES (?,?,?,?,?)",
                [$name,$email,$phone,$subject,$message]);
            $success=true;
        }
    }
}
$preSubject = clean($_GET['subject']??'');
require_once __DIR__.'/../includes/header.php';
?>
<section class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?=SITE_URL?>">Home</a><span>/</span>Contact</div>
    <div class="eyebrow">Get In Touch</div>
    <h1 class="sec-title" style="color:#fff">Contact Us</h1>
    <p class="page-hero-desc">We'd love to hear from you. Reach us for partnerships, enquiries, or just to say hello.</p>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <!-- Map -->
    <?php $mapEmbed=setting('maps_embed'); if($mapEmbed): ?>
    <div class="map-wrap fu" style="margin-bottom:48px">
      <iframe src="<?=e($mapEmbed)?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <?php endif; ?>

    <div class="contact-grid">
      <!-- Form -->
      <div class="fu">
        <?php if($success): ?>
        <div class="alert alert-success">✓ Thank you! Your message has been sent. We'll respond within 2 business days.</div>
        <?php endif; ?>
        <?php foreach($errors as $e): ?><div class="alert alert-error">✕ <?=e($e)?></div><?php endforeach; ?>
        <div class="contact-form-wrap">
          <h2 style="font-family:var(--font-h);font-size:20px;font-weight:900;text-transform:uppercase;margin-bottom:28px;color:var(--dark)">Send a Message</h2>
          <form id="contactForm" method="POST" action="">
            <?=csrfField()?>
            <div class="form-row-2">
              <div class="fg"><label class="fl">Full Name *</label><input class="fi" type="text" name="name" value="<?=e($_POST['name']??'')?>" required/></div>
              <div class="fg"><label class="fl">Email Address *</label><input class="fi" type="email" name="email" value="<?=e($_POST['email']??'')?>" required/></div>
            </div>
            <div class="form-row-2">
              <div class="fg"><label class="fl">Phone Number</label><input class="fi" type="tel" name="phone" value="<?=e($_POST['phone']??'')?>"/></div>
              <div class="fg"><label class="fl">Subject</label>
                <select class="fs" name="subject">
                  <option value="">Select a subject...</option>
                  <?php
                  $subs=['Export Partnership','Product Enquiry','Sample Request','Compliance Audit','Career Enquiry','Media Enquiry','Other'];
                  foreach($subs as $s): ?>
                  <option value="<?=e($s)?>" <?=($preSubject===$s||($_POST['subject']??'')===$s)?'selected':''?>><?=e($s)?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="fg"><label class="fl">Message *</label><textarea class="ft" name="message" required><?=e($_POST['message']??'')?></textarea></div>
            <button type="submit" class="btn btn-red" style="width:100%;padding:15px;font-size:12px">Send Message →</button>
          </form>
        </div>
      </div>

      <!-- Info panel -->
      <div class="fu">
        <div class="contact-info-panel">
          <div class="ci-heading">Contact Information</div>
          <?php
          $infos=[
            ['📍','Factory Address',setting('site_address')],
            ['📧','Email',setting('site_email')],
            ['📞','IP Phone',setting('site_phone1')."\n".setting('site_phone2')],
            ['📱','Phone',setting('site_phone3')."\n".setting('site_phone4')],
            ['📠','Fax',setting('site_fax')],
          ];
          foreach($infos as [$ico,$lbl,$val]):
            if(!trim($val)) continue;
          ?>
          <div class="ci-item">
            <div class="ci-icon"><?=$ico?></div>
            <div>
              <div class="ci-label"><?=e($lbl)?></div>
              <div class="ci-val"><?=nl2br(e($val))?></div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php if(setting('site_webmail')): ?>
          <div class="ci-item">
            <div class="ci-icon">🌐</div>
            <div>
              <div class="ci-label">Web Mail</div>
              <div class="ci-val"><a href="<?=e(setting('site_webmail'))?>" target="_blank"><?=e(setting('site_webmail'))?></a></div>
            </div>
          </div>
          <?php endif; ?>
          <div class="ci-item">
            <div class="ci-icon">🕐</div>
            <div>
              <div class="ci-label">Office Hours</div>
              <div class="ci-val">Sunday – Thursday: 9:00 AM – 6:00 PM<br/>Friday – Saturday: Closed</div>
            </div>
          </div>
        </div>

        <!-- Reg info -->
        <div style="background:var(--gray-bg);padding:28px;margin-top:20px;border-left:4px solid var(--red)">
          <div style="font-family:var(--font-h);font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;color:var(--dark);margin-bottom:14px">Registered Address</div>
          <p style="font-size:13px;color:#555;line-height:1.7"><?=e(setting('reg_address'))?><br/>
          <strong>Reg No.:</strong> <?=e(setting('reg_number'))?></p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
