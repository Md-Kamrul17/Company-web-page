// Admin JS
// Auto-hide alerts
document.querySelectorAll('.adm-alert').forEach(el => {
  setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .5s'; }, 4000);
  setTimeout(() => el.remove(), 4600);
});

// Image preview on file input change
document.querySelectorAll('input[type="file"]').forEach(input => {
  input.addEventListener('change', function() {
    const file = this.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const previewId = this.dataset.preview;
    let preview = previewId ? document.getElementById(previewId) : this.nextElementSibling;
    if (!preview || preview.tagName !== 'IMG') {
      preview = document.createElement('img');
      preview.className = 'img-preview';
      this.parentNode.insertBefore(preview, this.nextSibling);
    }
    preview.src = URL.createObjectURL(file);
  });
});

// Slug auto-gen from title
const titleField = document.getElementById('titleField');
const slugField  = document.getElementById('slugField');
if (titleField && slugField) {
  slugField.addEventListener('input', () => slugField.dataset.manual = '1');
  titleField.addEventListener('input', function() {
    if (!slugField.dataset.manual) {
      slugField.value = this.value.toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g,'').replace(/[\s-]+/g,'-').replace(/^-|-$/g,'');
    }
  });
}

// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', function(e) {
    if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
  });
});

// Sort order drag (simple up/down for sort tables)
document.querySelectorAll('.sort-up, .sort-down').forEach(btn => {
  btn.addEventListener('click', function() {
    const row = this.closest('tr');
    if (this.classList.contains('sort-up') && row.previousElementSibling) {
      row.parentNode.insertBefore(row, row.previousElementSibling);
    } else if (this.classList.contains('sort-down') && row.nextElementSibling) {
      row.parentNode.insertBefore(row.nextElementSibling, row);
    }
  });
});
