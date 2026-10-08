<?php
/*
 * MAF Footwear Limited — Company Info Update Tool
 * URL: http://localhost/maf-website/update-company-info.php
 * একবার run করুন, তারপর এই ফাইল মুছে ফেলুন
 */
require_once __DIR__ . '/config/db.php';

$updates = [];
$errors  = [];

$settings = [
    'site_name'      => 'MAF Footwear Limited',
    'site_tagline'   => '100% Export Oriented Quality Footwear Manufacturing Industry',
    'footer_tagline' => '100% export oriented quality athletic shoe, sandal, slipper & flip-flop manufacturing factory in Bangladesh.',
    'site_email'     => 'info@maffootwear.com',
    'site_phone1'    => '+880241388079',
    'site_phone2'    => '+880241388080',
    'site_phone3'    => '+880241388079',
    'site_phone4'    => '+880241388080',
    'site_fax'       => '+880241388078',
    'site_address'   => 'Plot no- 296, Nasirabad I/A, Polytechnic Circle, Chattogram.',
    'reg_address'    => 'Plot no- 296, Nasirabad I/A, Polytechnic Circle, Chattogram.',
    'site_webmail'   => 'http://www.maffootwear.com',
];

foreach ($settings as $key => $val) {
    try {
        DB::execute(
            "INSERT INTO site_settings (setting_key,setting_value) VALUES(?,?)
             ON DUPLICATE KEY UPDATE setting_value=?",
            [$key,$val,$val]
        );
        $updates[] = "✅ <strong>$key</strong> = ".htmlspecialchars($val);
    } catch(Exception $e){ $errors[] = "❌ $key: ".$e->getMessage(); }
}

// Replace company name in all content tables
$replacements = [
    ['news_articles',    ['title','excerpt','content']],
    ['home_intro',       ['heading','content']],
    ['company_pages',    ['page_title','content']],
    ['production_units', ['title','short_description','content']],
    ['operation_strength',['title','short_description','content']],
    ['vision_values',    ['section_title','content']],
    ['ticker_items',     ['text']],
    ['hero_slides',      ['title','subtitle','description']],
];

foreach($replacements as [$table,$cols]) {
    try {
        foreach($cols as $col) {
            DB::execute("UPDATE $table SET $col=REPLACE($col,'MAF Shoes Limited','MAF Footwear Limited')");
            DB::execute("UPDATE $table SET $col=REPLACE($col,'MAF Shoes','MAF Footwear')");
            DB::execute("UPDATE $table SET $col=REPLACE($col,'maf Shoes','maf Footwear')");
        }
        $updates[] = "✅ <strong>$table</strong> — company name আপডেট";
    } catch(Exception $e){ $errors[] = "❌ $table: ".$e->getMessage(); }
}

// Update hero slide 1 title
try {
    DB::execute("UPDATE hero_slides SET title='MAF FOOTWEAR LIMITED' WHERE sort_order=1");
    $updates[] = "✅ Hero slide title আপডেট";
} catch(Exception $e){ $errors[] = "❌ Hero: ".$e->getMessage(); }

$total    = count($updates);
$errCount = count($errors);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Update — MAF Footwear</title>
<style>
body{font-family:Arial,sans-serif;background:#f5f5f5;padding:40px 20px;margin:0}
.card{background:#fff;max-width:680px;margin:0 auto;padding:40px;
      border-top:4px solid <?=$errCount===0?'#22c55e':'#f59e0b'?>}
h2{margin:0 0 6px;color:<?=$errCount===0?'#15803d':'#92400e'?>}
p{color:#666;font-size:14px;margin:0 0 24px}
.summary{background:#f8f8f8;border:1px solid #e5e7eb;padding:14px 18px;
          font-size:14px;margin-bottom:20px;border-radius:2px}
.item{padding:8px 14px;margin-bottom:5px;font-size:13px}
.ok{background:#f0fdf4;border-left:3px solid #22c55e;color:#166534}
.er{background:#fef2f2;border-left:3px solid #ef4444;color:#991b1b}
.sec{font-weight:bold;font-size:10px;text-transform:uppercase;letter-spacing:1px;
     color:#999;margin:18px 0 8px}
.btn{display:inline-block;padding:12px 24px;color:#fff;font-weight:bold;
     font-size:14px;text-decoration:none;margin-right:8px;margin-top:20px}
.g{background:#22c55e}.r{background:#CC1E1E}
.warn{background:#fff3cd;border:1px solid #ffc107;padding:14px 18px;
      font-size:13px;color:#856404;margin-top:20px;line-height:1.7}
</style>
</head>
<body>
<div class="card">
  <h2><?=$errCount===0?'✅ সফলভাবে আপডেট হয়েছে!':'⚠️ আংশিক সম্পন্ন'?></h2>
  <p>MAF Shoes Limited → MAF Footwear Limited এবং নতুন address আপডেট করা হয়েছে।</p>
  <div class="summary">
    ✅ সফল: <strong><?=$total?></strong> &nbsp;|&nbsp; ❌ সমস্যা: <strong><?=$errCount?></strong>
  </div>
  <?php if($updates): ?>
  <div class="sec">সফল আপডেট</div>
  <?php foreach($updates as $u): ?><div class="item ok"><?=$u?></div><?php endforeach; ?>
  <?php endif; ?>
  <?php if($errors): ?>
  <div class="sec">সমস্যা</div>
  <?php foreach($errors as $e): ?><div class="item er"><?=htmlspecialchars($e)?></div><?php endforeach; ?>
  <?php endif; ?>
  <div>
    <a href="<?=SITE_URL?>/" class="btn g">🏠 Website দেখুন</a>
    <a href="<?=SITE_URL?>/admin/dashboard.php" class="btn r">⚙️ Dashboard</a>
  </div>
  <div class="warn">
    ⚠️ <strong>কাজ শেষে এই ফাইল মুছে ফেলুন:</strong><br/>
    <code>C:\xampp\htdocs\maf-website\update-company-info.php</code><br/><br/>
    🖼️ <strong>Logo যোগ করতে</strong> আপনার লোগো ছবিটা এখানে রাখুন:<br/>
    <code>C:\xampp\htdocs\maf-website\public\images\maf-logo.png</code>
  </div>
</div>
</body>
</html>
