# MAF Shoes Limited — Website Documentation
## Complete PHP + MySQL + Admin Dashboard

---

## 📋 PROJECT OVERVIEW

| Item | Detail |
|---|---|
| **Company** | MAF Shoes Limited |
| **Type** | Corporate / B2B Website |
| **Frontend** | Custom PHP (no framework) |
| **Backend** | Custom PHP with PDO |
| **Database** | MySQL 5.7+ / MariaDB 10.3+ |
| **Design** | Apex Footwear Ltd inspired premium UI |
| **Admin** | Full CMS dashboard |
| **PHP Version** | PHP 8.0+ required |

---

## 📁 FOLDER STRUCTURE

```
maf-website/
├── config/
│   ├── db.php              ← Database connection config (EDIT THIS)
│   └── install.sql         ← Full database schema + seed data
├── includes/
│   ├── functions.php       ← Core helpers, auth, uploads
│   ├── header.php          ← Public site nav/head
│   └── footer.php          ← Public site footer + social bar
├── pages/
│   ├── about/
│   │   ├── company-overview.php
│   │   ├── company-profile.php
│   │   ├── vision.php
│   │   ├── board.php
│   │   └── customers.php
│   ├── production/
│   │   ├── index.php       ← All 7 production units listing
│   │   └── detail.php      ← Single unit detail (uses ?slug=)
│   ├── operation/
│   │   ├── index.php       ← All 6 operation areas listing
│   │   └── detail.php      ← Single area detail (uses ?slug=)
│   ├── media/
│   │   ├── news.php        ← News listing with category filter
│   │   ├── news-detail.php ← Single article (uses ?slug=)
│   │   ├── gallery.php     ← Albums + lightbox
│   │   └── videos.php      ← Video grid
│   ├── products.php        ← Filterable product catalog
│   ├── jobs.php            ← Job listings with keyword search
│   └── contact.php         ← Contact form + Google Maps
├── public/
│   ├── css/style.css       ← Complete front-end stylesheet
│   ├── js/main.js          ← Slider, counters, filters, lightbox
│   └── uploads/            ← All uploaded media (auto-created)
│       ├── hero/           ← Hero slide backgrounds
│       ├── news/           ← News article thumbnails
│       ├── products/       ← Product catalog images
│       ├── gallery/        ← Gallery album images
│       ├── team/           ← Board member photos
│       ├── production/     ← Production unit images
│       ├── operation/      ← Operation area images
│       ├── videos/         ← Uploaded video files
│       └── docs/           ← PDF/document uploads
├── admin/
│   ├── login.php           ← Admin login
│   ├── logout.php          ← Destroys session
│   ├── dashboard.php       ← Overview stats + quick actions
│   ├── css/admin.css       ← Dashboard stylesheet
│   ├── js/admin.js         ← Dashboard JavaScript
│   ├── includes/
│   │   ├── header.php      ← Admin sidebar + topbar
│   │   └── footer.php      ← Closes admin layout
│   └── pages/
│       ├── hero-slides.php     ← Manage homepage hero slides
│       ├── home-stats.php      ← Manage animated stat counters
│       ├── home-intro.php      ← Manage homepage intro text
│       ├── ticker.php          ← Manage scrolling ticker text
│       ├── company-pages.php   ← Edit company overview/profile
│       ├── vision.php          ← Edit vision/purpose/values (EN+BN)
│       ├── board.php           ← Board of directors CRUD
│       ├── customers.php       ← Customer logos/brands CRUD
│       ├── production.php      ← Production units CRUD
│       ├── operation.php       ← Operation strength CRUD
│       ├── products.php        ← Product catalog CRUD
│       ├── markets.php         ← Export markets CRUD
│       ├── news.php            ← News articles listing
│       ├── news-edit.php       ← Create/edit news article
│       ├── gallery.php         ← Gallery albums + image upload
│       ├── videos.php          ← Videos management
│       ├── jobs.php            ← Job listings CRUD
│       ├── contacts.php        ← Read/manage enquiries
│       ├── settings.php        ← Site settings (contact, social)
│       ├── page-meta.php       ← SEO meta for all pages
│       └── admins.php          ← Admin user accounts
├── index.php               ← Homepage
├── 404.php                 ← 404 error page
├── .htaccess               ← Apache config (security, caching)
└── README.md               ← This file
```

---

## 🚀 INSTALLATION GUIDE

### Step 1 — Requirements

Make sure your server has:
- **PHP 8.0+** with extensions: `pdo_mysql`, `gd`, `fileinfo`, `mbstring`
- **MySQL 5.7+** or **MariaDB 10.3+**
- **Apache** with `mod_rewrite` enabled (for .htaccess)

> Works on: XAMPP, WAMP, Laragon, cPanel shared hosting, VPS (Ubuntu/CentOS)

---

### Step 2 — Upload Files

Copy the entire `maf-website/` folder to your web server:

- **Local (XAMPP):** `C:/xampp/htdocs/maf-website/`
- **Local (Laragon):** `C:/laragon/www/maf-website/`
- **cPanel:** Upload to `public_html/maf-website/` via File Manager or FTP

---

### Step 3 — Create Database

1. Open **phpMyAdmin** (or MySQL CLI)
2. Create a new database:
   ```sql
   CREATE DATABASE maf_shoes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Select the `maf_shoes` database
4. Click **Import** → choose `config/install.sql` → click **Go**

This creates all tables AND inserts all real MAF Shoes company data.

---

### Step 4 — Configure Database Connection

Open `config/db.php` and edit these values:

```php
define('DB_HOST', 'localhost');       // Usually 'localhost'
define('DB_USER', 'root');            // Your MySQL username
define('DB_PASS', '');                // Your MySQL password
define('DB_NAME', 'maf_shoes');       // Database name from Step 3

define('SITE_URL', 'http://localhost/maf-website');  // ← Your site URL
```

**For live hosting** (cPanel example):
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'youraccount_dbuser');
define('DB_PASS', 'yourDatabasePassword');
define('DB_NAME', 'youraccount_maf_shoes');
define('SITE_URL', 'https://www.mafshoes.com');
```

---

### Step 5 — Set Folder Permissions

The uploads folder needs write permission:

```bash
chmod 755 public/uploads/
chmod 755 public/uploads/*/
```

On cPanel hosting, File Manager → select `public/uploads/` → Permissions → set to 755.

---

### Step 6 — Admin Login

Visit: `http://localhost/maf-website/admin/login.php`

| Field | Value |
|---|---|
| Email | `admin@mafshoes.com` |
| Password | `MAF@Admin2024` |

**⚠️ IMPORTANT: Change the password immediately after first login!**

Go to: Admin Panel → Admin Users → Edit → set a new strong password.

---

### Step 7 — Verify the Site

Visit: `http://localhost/maf-website/`

You should see the fully-populated homepage with:
- ✅ Hero slider (4 slides)
- ✅ Scrolling ticker
- ✅ Stats counters (animated)
- ✅ Homepage intro text
- ✅ Production units preview
- ✅ Export markets
- ✅ Latest news (3 articles)
- ✅ Career banner
- ✅ Contact CTA

---

## 🎛️ ADMIN DASHBOARD GUIDE

### Accessing Admin
URL: `/admin/login.php`

### What You Can Manage

| Section | Admin Page | What It Controls |
|---|---|---|
| Hero Slides | Hero Slides | Homepage slider (title, subtitle, image, button) |
| Counters | Stats/Counters | Animated numbers (5000+, 7, 15+ etc.) |
| Intro Text | Homepage Intro | Text and heading below hero slider |
| Ticker | Ticker Items | Red scrolling text bar |
| Company Overview | Company Pages | Full page HTML content |
| Company Profile | Company Pages | Registration table, details |
| Vision/Values | Vision & Values | EN + Bengali bilingual content |
| Board | Board of Directors | Photos, names, designations |
| Customers | Customers | Brand logos and countries |
| Production Units | Production Units | All 7 units — title, content, images |
| Operation Areas | Operation Strength | All 6 areas — title, content, images |
| Products | Products | Upload product images by category |
| Export Markets | Export Markets | Country names and flag emojis |
| News | Newsroom | Articles with categories, thumbnail, HTML |
| Gallery | Gallery | Albums + multi-image upload |
| Videos | Videos | YouTube embeds or uploaded MP4s |
| Jobs | Job Listings | Title, dept, requirements, deadline |
| Messages | Contact Messages | Read incoming enquiries, reply via email |
| Site Settings | Site Settings | Email, phones, address, social links |
| Page SEO | Page SEO | Meta title + description for every page |
| Admin Users | Admin Users | Add/edit/remove admin accounts |

---

## 🔑 DEFAULT CREDENTIALS

| Role | Email | Password |
|---|---|---|
| Super Admin | admin@mafshoes.com | MAF@Admin2024 |

Change immediately after first login via **Admin Users** page.

---

## 📸 ADDING IMAGES

### Hero Background Images
- Recommended size: **1920 × 1080px**
- Format: JPEG or WebP
- Upload via: Admin → Hero Slides → Edit slide → Background Image

### Product Images
- Recommended size: **600 × 600px** (square)
- Format: JPEG or PNG
- Upload via: Admin → Products → Add Product

### Board Member Photos
- Recommended size: **400 × 400px** (square)
- Format: JPEG or PNG
- Upload via: Admin → Board of Directors → Edit member

### Gallery Images
- Any landscape size works
- Format: JPEG/PNG/WebP
- Upload via: Admin → Gallery → Select Album → Upload Images (multiple at once)

---

## 🌍 CHANGING SITE URL

If you move the site to a different domain or path, update **one line** in `config/db.php`:

```php
define('SITE_URL', 'https://www.yournewdomain.com');
```

No other files need to change — all links are built from this constant.

---

## 📧 CONTACT FORM

Contact form submissions are saved to the `contact_submissions` database table.

To view and reply: **Admin → Contact Messages**

To also receive email notifications, add this to `pages/contact.php` after the DB insert:
```php
mail('info@mafshoes.com', 'New Enquiry: '.$subject,
     "From: $name <$email>\n\n$message");
```

---

## 🔒 SECURITY NOTES

1. **Change admin password** on first login
2. **Keep PHP 8.x updated** on your server
3. **Enable HTTPS** — uncomment the redirect in `.htaccess` after adding SSL
4. The `config/` and `includes/` directories are blocked from direct browser access via `.htaccess`
5. All form inputs use **PDO prepared statements** — SQL injection protected
6. All output uses **htmlspecialchars()** — XSS protected
7. Forms use **CSRF tokens** — cross-site request forgery protected

---

## 🛠️ CUSTOMIZATION

### Change Brand Colors
Edit `public/css/style.css` — update the CSS variables at the top:
```css
:root {
  --red: #CC1E1E;      /* Primary brand red */
  --dark: #1C1C1C;     /* Dark charcoal */
}
```

### Add a New Page
1. Create `pages/your-page.php`
2. Add `define('PAGE_SLUG','your-page');` at top
3. `require_once __DIR__.'/../includes/functions.php';`
4. Add `require_once __DIR__.'/../includes/header.php';` after data queries
5. Write your HTML
6. End with `require_once __DIR__.'/../includes/footer.php';`
7. Add the link to nav in `includes/header.php`

### Add a New Admin Section
1. Copy an existing admin page from `admin/pages/`
2. Add your sidebar link in `admin/includes/header.php`
3. Create the DB table in `config/install.sql` (and run it)

---

## 📞 SUPPORT

For technical support or customization:
- Email: info@mafshoes.com
- Website: https://mafshoes.com

---

*Built with Custom PHP + MySQL | Design inspired by Apex Footwear Ltd*
