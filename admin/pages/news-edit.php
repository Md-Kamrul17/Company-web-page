<?php
$pageTitle='News Article';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../includes/header.php';
$id      = (int)($_GET['id']??0);
$article = $id ? DB::fetchOne("SELECT * FROM news_articles WHERE id=?",[$id]) : null;
$cats    = DB::fetchAll("SELECT * FROM news_categories ORDER BY name");
$errors  = [];

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verifyCsrf($_POST['csrf_token']??'')){ $errors[]='Security error.'; }
    $title    = clean($_POST['title']??'');
    $sl       = clean($_POST['slug']??'')?:slug($title);
    $catId    = (int)($_POST['category_id']??0)?:null;
    $excerpt  = clean($_POST['excerpt']??'');
    $content  = $_POST['content']??'';
    $featured = isset($_POST['is_featured'])?1:0;
    $pub      = isset($_POST['is_published'])?1:0;
    $pubAt    = clean($_POST['published_at']??date('Y-m-d H:i:s'));
    $mTitle   = clean($_POST['meta_title']??'');
    $mDesc    = clean($_POST['meta_desc']??'');
    if(!$title) $errors[]='Title is required.';
    // unique slug
    $ex=DB::fetchOne("SELECT id FROM news_articles WHERE slug=? AND id!=?",[$sl,$id]);
    if($ex) $sl.='-'.time();
    $thumb=$article['thumbnail']??null;
    if(!empty($_FILES['thumbnail']['name'])){
        $up=uploadFile($_FILES['thumbnail'],'news');
        if($up){ if($thumb) @unlink(UPLOAD_DIR.$thumb); $thumb=$up; }
        else $errors[]='Image upload failed (JPEG/PNG/WebP, max 5MB).';
    }
    if(!$errors){
        if($id){
            DB::execute("UPDATE news_articles SET category_id=?,title=?,slug=?,excerpt=?,content=?,thumbnail=?,is_featured=?,is_published=?,published_at=?,meta_title=?,meta_desc=? WHERE id=?",
                [$catId,$title,$sl,$excerpt,$content,$thumb,$featured,$pub,$pubAt,$mTitle,$mDesc,$id]);
            flash('success','Article updated.');
        } else {
            DB::execute("INSERT INTO news_articles (category_id,title,slug,excerpt,content,thumbnail,is_featured,is_published,published_at,meta_title,meta_desc) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
                [$catId,$title,$sl,$excerpt,$content,$thumb,$featured,$pub,$pubAt,$mTitle,$mDesc]);
            flash('success','Article created.');
        }
        redirect(SITE_URL.'/admin/pages/news.php');
    }
}
?>
<?php foreach($errors as $err): ?><div class="adm-alert adm-alert-error">✕ <?=e($err)?></div><?php endforeach; ?>
<div class="pg-header">
  <div class="pg-title"><?=$id?'Edit Article':'New Article'?></div>
  <a href="<?=SITE_URL?>/admin/pages/news.php" class="adm-btn adm-btn-out">← Back</a>
</div>
<form method="POST" enctype="multipart/form-data">
  <?=csrfField()?>
  <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">
    <div>
      <div class="panel">
        <div class="panel-head"><span class="panel-title">Article Content</span></div>
        <div class="panel-body">
          <div class="adm-fg">
            <label class="adm-fl">Title *</label>
            <input class="adm-fi" id="titleField" name="title" value="<?=e($article['title']??'')?>" required/>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">URL Slug</label>
            <input class="adm-fi" id="slugField" name="slug" value="<?=e($article['slug']??'')?>"/>
            <p class="adm-hint">Auto-generated. Only letters, numbers, hyphens.</p>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Excerpt / Summary</label>
            <textarea class="adm-ft" name="excerpt" rows="3"><?=e($article['excerpt']??'')?></textarea>
          </div>
          <div class="adm-fg">
            <label class="adm-fl">Full Content (HTML) *</label>
            <textarea class="adm-ft" name="content" rows="16" style="font-family:monospace;font-size:13px"><?=e($article['content']??'')?></textarea>
            <div class="html-note">⚠ Write HTML: &lt;p&gt;, &lt;h2&gt;, &lt;ul&gt;, &lt;strong&gt;, &lt;img&gt; etc.</div>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-head"><span class="panel-title">SEO / Meta</span></div>
        <div class="panel-body">
          <div class="adm-fg"><label class="adm-fl">Meta Title</label><input class="adm-fi" name="meta_title" value="<?=e($article['meta_title']??'')?>"/></div>
          <div class="adm-fg"><label class="adm-fl">Meta Description</label><textarea class="adm-ft" name="meta_desc" rows="2"><?=e($article['meta_desc']??'')?></textarea></div>
        </div>
      </div>
    </div>
    <div>
      <div class="panel">
        <div class="panel-head"><span class="panel-title">Publish</span></div>
        <div class="panel-body">
          <div class="adm-fg"><label class="adm-check"><input type="checkbox" name="is_published" <?=($article['is_published']??1)?'checked':''?>> Published (visible on site)</label></div>
          <div class="adm-fg"><label class="adm-check"><input type="checkbox" name="is_featured" <?=($article['is_featured']??0)?'checked':''?>> Featured on homepage</label></div>
          <div class="adm-fg">
            <label class="adm-fl">Publish Date &amp; Time</label>
            <input class="adm-fi" type="datetime-local" name="published_at" value="<?=date('Y-m-d\TH:i',strtotime($article['published_at']??'now'))?>"/>
          </div>
          <button type="submit" class="adm-btn adm-btn-red" style="width:100%;padding:13px"><?=$id?'💾 Update':'✓ Create'?> Article</button>
        </div>
      </div>
      <div class="panel">
        <div class="panel-head"><span class="panel-title">Category</span></div>
        <div class="panel-body">
          <select class="adm-fs" name="category_id">
            <option value="">— None —</option>
            <?php foreach($cats as $c): ?>
            <option value="<?=$c['id']?>" <?=($article['category_id']??'')==$c['id']?'selected':''?>><?=e($c['name'])?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="panel">
        <div class="panel-head"><span class="panel-title">Thumbnail</span></div>
        <div class="panel-body">
          <?php if(!empty($article['thumbnail'])): ?>
          <div class="cur-file">Current image:</div>
          <img src="<?=mediaUrl($article['thumbnail'])?>" class="img-preview" alt="thumbnail"/>
          <div style="margin-top:12px"></div>
          <?php endif; ?>
          <input class="adm-fi" type="file" name="thumbnail" accept="image/*" data-preview="thumbPreview"/>
          <img id="thumbPreview" class="img-preview" style="display:none" alt="preview"/>
          <p class="adm-hint">JPEG/PNG/WebP. Max 5MB. Recommended 800×500px.</p>
        </div>
      </div>
    </div>
  </div>
</form>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
