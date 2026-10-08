<?php /* home-intro.php */
$pageTitle='Homepage Intro';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??''))die('err');
    $heading=clean($_POST['heading']??'');$content=$_POST['content']??'';
    $exists=DB::fetchOne("SELECT id FROM home_intro LIMIT 1");
    if($exists)DB::execute("UPDATE home_intro SET heading=?,content=? WHERE id=?",[$heading,$content,$exists['id']]);
    else DB::execute("INSERT INTO home_intro (heading,content) VALUES (?,?)",[$heading,$content]);
    /*flash('success','Homepage intro updated.');redirect(SITE_URL.'/admin/pages/home-intro.php');  ======== Old Line ======*/  
     // ছবি আপলোড / পরিবর্তন / মুছে ফেলা
    $saveImg=function(string $v){DB::execute("INSERT INTO site_settings (setting_key,setting_value) VALUES('home_intro_image',?) ON DUPLICATE KEY UPDATE setting_value=?",[$v,$v]);};
    $oldImg=setting('home_intro_image','');
    $imgFailed=false;
    if(!empty($_FILES['intro_image']['name'])){
        $up=uploadFile($_FILES['intro_image'],'intro');
        if($up){if($oldImg)@unlink(UPLOAD_DIR.$oldImg);$saveImg($up);}
        else $imgFailed=true;
    }elseif(!empty($_POST['remove_intro_image'])&&$oldImg){
        @unlink(UPLOAD_DIR.$oldImg);$saveImg('');
    }
    if($imgFailed)flash('error','Intro text saved, but the image was NOT uploaded. Use JPG, PNG, WebP or GIF, max 8 MB.');
    else flash('success','Homepage intro updated.');
    redirect(SITE_URL.'/admin/pages/home-intro.php');
    // ছবি আপলোড / পরিবর্তন / মুছে ফেলা
}
$intro=DB::fetchOne("SELECT * FROM home_intro LIMIT 1");
      /*<!--new line only 2 line-->*/
$introImg=setting('home_intro_image','');
$hasImg=$introImg&&is_file(UPLOAD_DIR.$introImg);
?>

<?=flashHtml()?>
<div class="pg-header"><div class="pg-title">Homepage Intro Text</div></div>
<div class="panel">
  <div class="panel-head"><span class="panel-title">Intro Section (below hero slider)</span></div>
  <div class="panel-body">

   <!--- <form method="POST"><?=csrfField()?>  --->   <!--/* old line */ -->

   <form method="POST" enctype="multipart/form-data"><?=csrfField()?>

      <div class="adm-fg"><label class="adm-fl">Heading</label><input class="adm-fi" name="heading" value="<?=e($intro['heading']??'Leader in Shoe Export')?>"/></div>
      <div class="adm-fg">
        <label class="adm-fl">Content (HTML)</label>
        <textarea class="adm-ft" name="content" rows="16" style="font-family:monospace;font-size:13px"><?=e($intro['content']??'')?></textarea>
        <div class="html-note">⚠ Write HTML paragraphs: &lt;p&gt;Your text here.&lt;/p&gt;</div>
      </div>
      
      /*<!--new line -->*/
            <div class="adm-fg">
        <label class="adm-fl">Intro Image (shown on the right side of this section)</label>
        <?php if($hasImg): ?>
        <img src="<?=e(mediaUrl($introImg))?>" class="img-preview" style="max-width:280px;max-height:240px" alt="Current intro image"/>
        <label style="display:block;margin:8px 0 12px;font-size:12px;color:var(--muted)"><input type="checkbox" name="remove_intro_image" value="1"/> Remove this image (homepage will show the default placeholder)</label>
        <?php else: ?>
        <div class="adm-hint" style="margin:0 0 8px">No image uploaded yet, so the homepage shows the default "MAF Shoes Factory" placeholder.</div>
        <?php endif; ?>
        <input class="adm-fi" type="file" name="intro_image" accept="image/png,image/jpeg,image/webp,image/gif"/>
        <div class="adm-hint">JPG, PNG, WebP or GIF, max 8 MB. Landscape ছবি (আনুমানিক 1000×860px) সবচেয়ে ভালো ফিট হবে।</div>
      </div>
      /*<!--new line -->*/

      <button type="submit" class="adm-btn adm-btn-red">💾 Save Intro</button>
    </form>
  </div>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
