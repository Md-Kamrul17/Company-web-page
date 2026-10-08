<?php
// Redirect to products.php with edit=0 (add new product)
require_once __DIR__.'/../../includes/functions.php';
redirect(SITE_URL.'/admin/pages/products.php?edit=0');
