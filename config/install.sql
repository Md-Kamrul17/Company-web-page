-- ================================================================
-- MAF Footwear Limited — Complete Database Schema + Seed Data
-- Run once to install the entire website database
-- ================================================================

CREATE DATABASE IF NOT EXISTS maf_footwear CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE maf_footwear;

-- ── ADMIN USERS ─────────────────────────────────────────────────
CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('super_admin','editor') DEFAULT 'editor',
  last_login DATETIME DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── SITE SETTINGS ───────────────────────────────────────────────
CREATE TABLE site_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT,
  setting_group VARCHAR(50) DEFAULT 'general',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── HERO SLIDES ─────────────────────────────────────────────────
CREATE TABLE hero_slides (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle TEXT,
  description TEXT,
  btn_text VARCHAR(80),
  btn_link VARCHAR(255),
  image VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── HOME STATS / COUNTERS ────────────────────────────────────────
CREATE TABLE home_stats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  value VARCHAR(20) NOT NULL,
  suffix VARCHAR(10) DEFAULT '',
  label VARCHAR(100) NOT NULL,
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── HOME QUICK LINKS (3 cards below intro) ───────────────────────
CREATE TABLE home_quick_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  link VARCHAR(255),
  image VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── HOME INTRO TEXT ──────────────────────────────────────────────
CREATE TABLE home_intro (
  id INT AUTO_INCREMENT PRIMARY KEY,
  heading VARCHAR(255),
  content LONGTEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── COMPANY INFO PAGES ───────────────────────────────────────────
CREATE TABLE company_pages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  page_slug VARCHAR(80) NOT NULL UNIQUE,
  page_title VARCHAR(200) NOT NULL,
  content LONGTEXT,
  image VARCHAR(255),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── VISION / PURPOSE / CORE VALUES ──────────────────────────────
CREATE TABLE vision_values (
  id INT AUTO_INCREMENT PRIMARY KEY,
  section_key VARCHAR(50) NOT NULL UNIQUE,  -- vision, purpose, core_values, vision_bn, purpose_bn, core_values_bn
  section_title VARCHAR(100),
  content TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── BOARD OF DIRECTORS ───────────────────────────────────────────
CREATE TABLE board_members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  designation VARCHAR(150) NOT NULL,
  bio TEXT,
  photo VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── CUSTOMERS / CLIENTS ──────────────────────────────────────────
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  logo VARCHAR(255),
  country VARCHAR(80),
  website VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── PRODUCTION UNITS ─────────────────────────────────────────────
CREATE TABLE production_units (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(80) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  short_description TEXT,
  content LONGTEXT,
  image VARCHAR(255),
  cover_image VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── OPERATION STRENGTH ───────────────────────────────────────────
CREATE TABLE operation_strength (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(80) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  short_description TEXT,
  content LONGTEXT,
  image VARCHAR(255),
  cover_image VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── PRODUCT CATEGORIES ───────────────────────────────────────────
CREATE TABLE product_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL UNIQUE,
  sort_order INT DEFAULT 0
);

-- ── PRODUCTS ─────────────────────────────────────────────────────
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  title VARCHAR(200),
  image VARCHAR(255) NOT NULL,
  description TEXT,
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE SET NULL
);

-- ── NEWS CATEGORIES ──────────────────────────────────────────────
CREATE TABLE news_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL UNIQUE
);

-- ── NEWS ARTICLES ────────────────────────────────────────────────
CREATE TABLE news_articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  title VARCHAR(300) NOT NULL,
  slug VARCHAR(300) NOT NULL UNIQUE,
  excerpt TEXT,
  content LONGTEXT,
  thumbnail VARCHAR(255),
  is_featured TINYINT(1) DEFAULT 0,
  is_published TINYINT(1) DEFAULT 1,
  published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  meta_title VARCHAR(255),
  meta_desc TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES news_categories(id) ON DELETE SET NULL
);

-- ── GALLERY ALBUMS ───────────────────────────────────────────────
CREATE TABLE gallery_albums (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  cover_image VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── GALLERY IMAGES ───────────────────────────────────────────────
CREATE TABLE gallery_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  album_id INT NOT NULL,
  caption VARCHAR(255),
  image VARCHAR(255) NOT NULL,
  sort_order INT DEFAULT 0,
  FOREIGN KEY (album_id) REFERENCES gallery_albums(id) ON DELETE CASCADE
);

-- ── VIDEOS ───────────────────────────────────────────────────────
CREATE TABLE videos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  video_type ENUM('upload','youtube','vimeo') DEFAULT 'youtube',
  video_url VARCHAR(500),
  thumbnail VARCHAR(255),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── JOBS ─────────────────────────────────────────────────────────
CREATE TABLE jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  department VARCHAR(100),
  location VARCHAR(100) DEFAULT 'Chittagong, Bangladesh',
  job_type ENUM('Full-time','Part-time','Contract') DEFAULT 'Full-time',
  description TEXT,
  requirements TEXT,
  responsibilities TEXT,
  deadline DATE,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── CONTACT SUBMISSIONS ──────────────────────────────────────────
CREATE TABLE contact_submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(30),
  subject VARCHAR(200),
  message TEXT NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── PAGE META ─────────────────────────────────────────────────────
CREATE TABLE page_meta (
  id INT AUTO_INCREMENT PRIMARY KEY,
  page_slug VARCHAR(100) NOT NULL UNIQUE,
  meta_title VARCHAR(255),
  meta_description TEXT,
  og_image VARCHAR(255),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── TICKER ITEMS ──────────────────────────────────────────────────
CREATE TABLE ticker_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  text VARCHAR(200) NOT NULL,
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);

-- ── EXPORT MARKETS ────────────────────────────────────────────────
CREATE TABLE export_markets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  country VARCHAR(100) NOT NULL,
  flag_emoji VARCHAR(10),
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1
);


-- ================================================================
-- SEED DATA — Real MAF Shoes Content
-- ================================================================

-- Admin: password = MAF@Admin2024 (bcrypt hash)
INSERT INTO admin_users (name, email, password, role) VALUES
('MAF Admin', 'admin@mafshoes.com', '$2y$10$TKh8H1.PJy5FgHoU.I7NXuOhE7lv5BJ0ZSoRjj7z1A2xFwMqNP4Nm', 'super_admin');
-- Default password: MAF@Admin2024

-- ── SITE SETTINGS ────────────────────────────────────────────────
INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES
('site_name',        'MAF Footwear Limited',                                           'general'),
('site_tagline',     '100% Export Oriented Quality Shoe Manufacturing Industry',     'general'),
('site_email',       'info@maffootwear.com',                                            'contact'),
('site_phone1',      '+8809610818283',                                               'contact'),
('site_phone2',      '+8809610818284',                                               'contact'),
('site_phone3',      '+880241388079',                                                'contact'),
('site_phone4',      '+880241388080',                                                'contact'),
('site_fax',         '+880241388078',                                                'contact'),
('site_address',     'Plot no- 296, Nasirabad I/A, Polytechnic Circle, Chattogram',     'contact'),
('site_webmail',     'https://mail.mafshoes.com',                                    'contact'),
('maps_embed',       'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2193.448532690531!2d91.86253724900989!3d22.39414634336168!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30ad2795538ce2fb%3A0xcb1bc6b5a2a90cf7!2smaf+Shoes+Ltd!5e0!3m2!1sbn!2sbd!4v1536648356687', 'contact'),
('facebook_url',     'https://www.facebook.com/Maf-Shoes-Ltd-189064104523790/',      'social'),
('youtube_url',      'https://youtube.com',                                          'social'),
('linkedin_url',     'https://linkedin.com',                                         'social'),
('footer_tagline',   '100% export oriented quality athletic shoe, sandal, slipper & flip-flop manufacturing factory in Bangladesh.', 'general'),
('established_year', '2007',                                                          'general'),
('reg_number',       'CH-6330',                                                       'general'),
('reg_address',      '83, Khatungonj, District: Chattogram',                          'general');

-- ── HERO SLIDES ──────────────────────────────────────────────────
INSERT INTO hero_slides (title, subtitle, description, btn_text, btn_link, sort_order) VALUES
('MAF SHOES LIMITED',
 '100% Export Oriented Quality Shoe Manufacturing',
 'Export to FRANCE, SPAIN, BRAZIL, JAPAN and other EU countries.',
 'Know More',
 'pages/about/company-overview.php',
 1),
('VERTICAL PRODUCTION UNITS',
 'Only shoe factory who produces various footwear related finish products and semi finished products in same factory premises',
 'Seven dedicated production units under one roof — from EVA foam to finished athletic shoes.',
 'Visit Production Units',
 'pages/production/index.php',
 2),
('OPERATION STRENGTH',
 'Well equipped and well experienced product development team',
 'Providing instant technical and production support from scratch to finished product.',
 'Know Our Strength',
 'pages/operation/index.php',
 3),
('OUR PRODUCTS',
 'Athletic Shoes, Sports Shoes, Casual Shoes, Direct Injection Shoes',
 'Flip-Flop, Slippers, Rubber Sole, IMEVA Sole, PU Sole, EVA Foam Sheet',
 'View Product Catalog',
 'pages/products.php',
 4);

-- ── HOME STATS ───────────────────────────────────────────────────
INSERT INTO home_stats (value, suffix, label, sort_order) VALUES
('7',    '',   'Production Units',        1),
('15',   '+',  'Years of Excellence',     2),
('5000', '+',  'Workforce',               3),
('20',   '+',  'Export Countries',        4);

-- ── HOME QUICK LINKS ─────────────────────────────────────────────
INSERT INTO home_quick_links (title, link, sort_order) VALUES
('Visit Our Production Units', 'pages/production/index.php', 1),
('Know Our Operation Strength', 'pages/operation/index.php', 2),
('See Our Product Catalog', 'pages/products.php', 3);

-- ── HOME INTRO ───────────────────────────────────────────────────
INSERT INTO home_intro (heading, content) VALUES
('Leader in Shoe Export',
'<p>MAF Footwear Limited is a 100% export oriented quality athletic shoe, sandal, slipper, &amp; flip-flop manufacturing factory with the ability to deliver impeccable quality and punctual shipment within a framework through a professional, reliable and sincere business atmosphere.</p>
<p>MAF Footwear Limited manufactures a wide range of footwear for all age group and it equipped with vertical production units. MAF Footwear Limited is an only shoe factory who produces various footwear related finish products and semi finished products in same factory premises.</p>
<p>For every footwear EVA foam is an essential materials requirement and MAF Footwear Limited have its own EVA foam production unit to produce EVA foam from recipe development to finish foam as per requirements.</p>
<p>To make production smoother and to reduce lead time, it produces rubber sole, PU sole and IMEVA (EVA injection) Soles for its product in same factory premises.</p>');

-- ── COMPANY PAGES ────────────────────────────────────────────────
INSERT INTO company_pages (page_slug, page_title, content) VALUES
('company-overview', 'Company Overview',
'<p>MAF Footwear Limited is a 100% export oriented quality athletic shoe, sandal, slipper, &amp; flip-flop manufacturing factory with the ability to deliver impeccable quality and punctual shipment within a framework through a professional, reliable and sincere business atmosphere.</p>
<p>MAF Footwear Limited manufactures a wide range of footwear for all age group and it equipped with vertical production units. MAF Footwear Limited is an only shoe factory who produces various footwear related finish products and semi finished products in same factory premises.</p>
<p>For every footwear EVA foam is an essential materials requirement and MAF Footwear Limited have its own EVA foam production unit to produce EVA foam from recipe development to finish foam as per requirements.</p>
<p>To make production smoother and to reduce lead time, it produces rubber sole, PU sole and IMEVA (EVA injection) Soles for its product in same factory premises.</p>
<p>We can proudly declare that MAF Footwear Limited is the most diversified factory with wide range of product and facilities.</p>
<p>To maintain global standard and prompt delivery, the company sources best quality raw materials from all over the world.</p>
<p>The company has well equipped and well experienced product development team that provides instant technical and production support. The technical team manages and follows manufacturing flow from scratch to finished product and also involves in improving the efficiency through continuous R&amp;D. The company''s high end laboratory checks physical properties of each and every pair of shoes in bulk production. MAF Footwear Limited is well compliant by European Customers in terms of Social, Environmental, Quality Assurance, Safety and Security.</p>
<p>Within a very short period of time, MAF Footwear Limited has acclaimed credit from its international customers for ensuring global standard of quality. The Company is currently operating in the export market of France, Spain, Japan, Brazil and other EU countries and exploring new export markets to establish its strong footprint.</p>'),

('company-profile', 'Company Profile',
'<h3>Company Snapshot</h3>
<table class="info-table">
<tr><th>Company Name</th><td>MAF Footwear Limited</td></tr>
<tr><th>Registration No.</th><td>CH-6330</td></tr>
<tr><th>Registration Date</th><td>31 July 2007</td></tr>
<tr><th>Registered Address</th><td>83, Khatungonj, District: Chattogram</td></tr>
<tr><th>Factory Address</th><td>Plot no- 296, Nasirabad I/A, Polytechnic Circle, Chattogram</td></tr>
<tr><th>Business Type</th><td>100% Export Oriented Manufacturing</td></tr>
<tr><th>Product Range</th><td>Athletic Shoes, Sports Shoes, Casual Shoes, Direct Injection Shoes, Flip-Flop, Slippers, Rubber Sole, IMEVA Sole, PU Sole, EVA Foam Sheet</td></tr>
<tr><th>Export Markets</th><td>France, Spain, Brazil, Japan, and other EU countries</td></tr>
<tr><th>Email</th><td>info@maffootwear.com</td></tr>
<tr><th>Phone</th><td>+8809610818283, +8809610818284</td></tr>
</table>');

-- ── VISION / VALUES ──────────────────────────────────────────────
INSERT INTO vision_values (section_key, section_title, content) VALUES
('vision',        'Vision',
 'Offering best in class sustainable Footwear Manufacturing facilities, delight our Customers with excellence and value creation, ensure better life to our Employees and Community.'),
('purpose',       'Purpose',
 '"To Create Happiness Under Feet." We work to make perfect shoes for every foot, our shoes help people feel good and look good.'),
('core_values',   'Core Values',
 'Integrity, Respect, Responsibility, Pioneering spirit.'),
('vision_bn',     'লক্ষ্য',
 'আমরা সর্বোত্তম ও টেকসই জুতা উৎপাদনকারী প্রতিষ্ঠান, পণ্যের উৎকর্ষ ও মূল্য সংযোজনের মাধ্যমে ক্রেতার সন্তুষ্টি অর্জন করি এবং আমাদের কর্মী ও সমাজের উন্নততর জীবনমান নিশ্চিত করি।'),
('purpose_bn',    'উদ্দেশ্য',
 '"পদতলে সুখের পরশ" প্রতিটি পায়ের জন্য যথাযথ জুতা তৈরি করার লক্ষ্যে আমরা কাজ করি। আমাদের তৈরি জুতায় ব্যক্তি স্বাচ্ছন্দ্য ও মার্জিত বোধ করে।'),
('core_values_bn','মূল্যবোধ',
 'সততা, সম্মান, দায়িত্ববোধ, অগ্রগামীতা।');

-- ── BOARD OF DIRECTORS ───────────────────────────────────────────
INSERT INTO board_members (name, designation, sort_order) VALUES
('Talha Bin Tayab (Abir)',       'Chairman',                           1),
('Hasnat Md. Abu Obida (Marshall)', 'Managing Director',              2),
('Abu Sadat MD. Faisal',         'Deputy Managing Director',          3),
('Mohammad Shahadat Ullah',      'Executive Director',                4),
('Dr. Farhana Afroze',           'Director — Operations',             5),
('Dr. Roksana Afrose',           'Director — Human Resource Department', 6);

-- ── PRODUCTION UNITS ─────────────────────────────────────────────
INSERT INTO production_units (slug, title, short_description, content, sort_order) VALUES
('athletic-sports-shoe', 'Athletic / Sports Shoe',
 'State-of-the-art manufacturing lines producing premium athletic and sports footwear for global brands.',
 '<p>MAF Footwear Limited''s Athletic/Sports Shoe production unit is equipped with modern assembly lines capable of producing high-performance footwear for international athletic brands. Our production team ensures every pair meets the strictest quality standards from upper cutting to final finishing.</p><p>We specialize in running shoes, training shoes, basketball shoes, and all types of athletic footwear with various sole technologies including rubber, PU, and EVA compounds.</p>', 1),

('direct-injection-shoes', 'Direct Injection Shoes',
 'Advanced direct injection technology producing durable and comfortable footwear with seamless sole bonding.',
 '<p>Our Direct Injection Shoe unit uses state-of-the-art rotary injection machines to produce footwear where the sole is injected directly onto the upper. This technology ensures superior bonding, comfort, and durability with reduced production time.</p><p>Direct injection technology is ideal for school shoes, casual shoes, safety footwear, and occupational footwear requiring maximum durability.</p>', 2),

('flip-flop', 'Flip-Flop',
 'High-capacity flip-flop production lines delivering millions of pairs annually for global retail brands.',
 '<p>Our dedicated Flip-Flop production unit is one of our highest-capacity units, producing a wide variety of EVA, rubber, and PU flip-flops in countless styles, colors, and sizes. We produce for all age groups from children to adults.</p><p>Our flip-flops are exported to major retail brands across Europe, Americas, and Asia-Pacific markets.</p>', 3),

('rubber-pu-sole', 'Rubber & PU Sole',
 'In-house rubber and polyurethane sole manufacturing ensuring quality control from compound to finished sole.',
 '<p>MAF Footwear Limited operates its own Rubber and PU Sole manufacturing unit, giving us complete control over one of the most critical components of footwear. Our compound laboratory develops custom rubber formulations to meet specific hardness, flexibility, and durability requirements.</p><p>In-house sole production significantly reduces lead times and allows us to offer bespoke sole solutions to our international customers.</p>', 4),

('imeva-sole-midsole', 'IMEVA Sole / Midsole',
 'Injection-molded EVA sole and midsole production for lightweight, cushioned athletic footwear.',
 '<p>Our IMEVA (Injection Molded EVA) production unit produces lightweight, high-cushion soles and midsoles critical for athletic and casual footwear. IMEVA soles offer superior shock absorption, flexibility, and durability compared to traditional compressed EVA.</p><p>We operate multiple injection molding presses capable of producing complex midsole geometries with precise density control.</p>', 5),

('eva-foam', 'EVA Foam',
 'Own EVA foam production from recipe development to finished foam sheets — a unique vertical integration capability.',
 '<p>MAF Footwear Limited has its own EVA (Ethylene Vinyl Acetate) foam production unit — a rare capability that sets us apart from other manufacturers. We produce EVA foam from raw compound development through sheet production, cutting, and shaping.</p><p>Our foam laboratory develops custom EVA recipes to achieve specific hardness, density, and color requirements for our customers. This vertical integration ensures consistent foam quality and dramatically reduces lead times.</p>', 6),

('embroidery', 'Embroidery',
 'In-house computerized embroidery unit providing decorative stitching on uppers and garment components.',
 '<p>Our computerized Embroidery unit provides in-house decorative stitching capabilities for shoe uppers, labels, and garment components. Equipped with multi-head computerized embroidery machines, we can execute complex brand logos, patterns, and decorative elements with precision.</p><p>In-house embroidery reduces dependency on subcontractors, maintains quality control, and allows faster sampling and production for our global customers.</p>', 7);

-- ── OPERATION STRENGTH ───────────────────────────────────────────
INSERT INTO operation_strength (slug, title, short_description, content, sort_order) VALUES
('people', 'People',
 'Over 5,000 skilled workers and professionals dedicated to delivering excellence in every pair of shoes.',
 '<p>Our people are our greatest asset. MAF Footwear Limited employs over 5,000 skilled workers, technicians, and professionals across all departments. We invest heavily in our workforce through regular training, skill development programs, and a safe, supportive working environment.</p><p>We maintain strict compliance with all labor laws and international worker welfare standards. Our workforce includes experts in shoe manufacturing, quality control, product development, compliance, and factory management.</p>', 1),

('product-development-center', 'Product Development Center',
 'A fully equipped PDC with experienced designers and developers who bring customer concepts to production-ready prototypes.',
 '<p>Our Product Development Center (PDC) is the creative and technical heart of MAF Footwear Limited. Our experienced team of designers and developers work closely with international brand customers to translate concepts and CAD designs into production-ready prototypes.</p><p>The PDC is equipped with advanced 3D design tools, pattern CAD systems, sample-making equipment, and all material resources needed to produce samples quickly. We can deliver initial samples within tight timelines to meet international brand calendars.</p>', 2),

('quality-control', 'Quality Control',
 'Rigorous multi-stage quality inspection from raw materials to finished pairs ensuring zero-defect shipments.',
 '<p>Quality Control at MAF Footwear Limited is a comprehensive, multi-stage process integrated throughout the entire production flow. Our QC team inspects incoming raw materials, monitors in-process production, and conducts final inspection on every shipment.</p><p>We operate against detailed inspection protocols aligned with international standards and customer-specific quality manuals. Our QC systems include AQL sampling, visual inspection, physical testing, and barcode tracking of each production lot.</p>', 3),

('internal-lab', 'Internal Lab',
 'High-end internal testing laboratory checking physical properties of every bulk production pair.',
 '<p>MAF Footwear Limited operates a high-end Internal Testing Laboratory equipped to conduct physical and chemical tests on footwear components and finished pairs. Our lab technicians test for abrasion resistance, flex resistance, bond strength, hardness, compression set, and chemical compliance.</p><p>The laboratory also conducts restricted substance testing and maintains compliance with EU REACH regulations, ensuring our products are safe for consumers and compliant with European environmental standards.</p>', 4),

('compliance', 'Compliance',
 'Full social, environmental, and quality compliance meeting European customer requirements and international standards.',
 '<p>Compliance is fundamental to MAF Footwear Limited''s operations. We maintain full compliance with international social, environmental, quality, and security standards as required by our European customers.</p><p>Our compliance framework covers: Social compliance (fair wages, working hours, worker welfare), Environmental compliance (waste management, chemical handling, energy efficiency), Quality management (ISO-aligned QMS), Security compliance (C-TPAT/BSCI requirements), and Chemical compliance (REACH, RSL testing).</p>', 5),

('training-center', 'Training Center',
 'Dedicated training facility developing worker skills in manufacturing, quality, compliance, and safety.',
 '<p>MAF Footwear Limited''s Training Center is dedicated to the continuous development of our workforce. We conduct regular training programs covering shoe manufacturing techniques, quality control procedures, machine operation, workplace safety, fire safety, and compliance awareness.</p><p>New workers go through a structured induction and basic skills training program before joining the production lines. We also conduct specialized training for supervisors and mid-level management in production planning, efficiency optimization, and leadership skills.</p>', 6);

-- ── PRODUCT CATEGORIES ───────────────────────────────────────────
INSERT INTO product_categories (name, slug, sort_order) VALUES
('All Groups', 'all',        0),
('Casual',     'casual',     1),
('Flip Flops', 'flip-flops', 2),
('Sandals',    'sandals',    3),
('Sports',     'sports',     4);

-- ── EXPORT MARKETS ───────────────────────────────────────────────
INSERT INTO export_markets (country, flag_emoji, sort_order) VALUES
('France',   '🇫🇷', 1),
('Spain',    '🇪🇸', 2),
('Brazil',   '🇧🇷', 3),
('Japan',    '🇯🇵', 4),
('Germany',  '🇩🇪', 5),
('Italy',    '🇮🇹', 6),
('Portugal', '🇵🇹', 7),
('Belgium',  '🇧🇪', 8),
('Netherlands', '🇳🇱', 9),
('Sweden',   '🇸🇪', 10);

-- ── NEWS CATEGORIES ──────────────────────────────────────────────
INSERT INTO news_categories (name, slug) VALUES
('Corporate',     'corporate'),
('Production',    'production'),
('Compliance',    'compliance'),
('Export',        'export'),
('People',        'people');

-- ── SAMPLE NEWS ARTICLES ─────────────────────────────────────────
INSERT INTO news_articles (category_id, title, slug, excerpt, content, is_featured, is_published, published_at) VALUES
(1, 'MAF Footwear Limited Achieves BSCI Certification for Social Compliance',
 'maf-shoes-bsci-certification',
 'MAF Footwear Limited has successfully passed the Business Social Compliance Initiative (BSCI) audit, reinforcing our commitment to ethical manufacturing.',
 '<p>MAF Footwear Limited is proud to announce successful completion of the Business Social Compliance Initiative (BSCI) audit, one of the most rigorous social compliance frameworks required by European retail brands.</p><p>The audit evaluated our factory across 13 performance areas including freedom of association, fair remuneration, occupational health and safety, working hours, and management systems. We achieved an outstanding rating across all categories.</p><p>"This certification validates our long-standing commitment to fair, safe, and ethical manufacturing," said the Managing Director. "Our workers are our most valuable asset, and ensuring their wellbeing is at the core of how we operate."</p>',
 1, 1, '2024-06-15 09:00:00'),

(4, 'MAF Shoes Expands Export Operations to New EU Markets',
 'maf-shoes-new-eu-markets',
 'Building on strong partnerships in France, Spain and Germany, MAF Footwear Limited announces new export relationships with buyers in Portugal and Belgium.',
 '<p>MAF Footwear Limited continues its export growth trajectory with the announcement of new buyer partnerships in Portugal and Belgium, expanding our EU footprint beyond our established markets in France, Spain, and Germany.</p><p>The new partnerships cover athletic footwear, flip-flops, and casual shoe categories with initial orders placed for the upcoming Spring/Summer season.</p>',
 0, 1, '2024-04-20 09:00:00'),

(2, 'New Direct Injection Production Line Commissioned at Chandgaon Factory',
 'new-direct-injection-line',
 'MAF Footwear Limited has commissioned a new high-speed direct injection production line, increasing our DI shoe capacity by 40%.',
 '<p>MAF Footwear Limited is pleased to announce the successful commissioning of a new high-speed direct injection shoe production line at our Chandgaon Industrial Area factory in Chittagong.</p><p>The new line features 48-station rotary injection machines with advanced temperature control and real-time production monitoring systems. This investment increases our direct injection shoe capacity by 40%, enabling us to take on additional volume orders from existing and new buyers.</p>',
 0, 1, '2024-02-10 09:00:00');

-- ── GALLERY ALBUMS ───────────────────────────────────────────────
INSERT INTO gallery_albums (title, description, sort_order) VALUES
('Factory Floor',    'A look inside our production facilities at Chandgaon, Chittagong', 1),
('Product Showcase', 'Highlights from our athletic shoe, flip-flop, and casual shoe product range', 2),
('Team & People',    'Our dedicated workforce of 5000+ skilled professionals', 3),
('Compliance Audit', 'International compliance audit activities and certifications', 4),
('Export Dispatch',  'Ready-for-export shipment preparation and logistics', 5),
('Events',           'Company events, training sessions, and milestones', 6);

-- ── VIDEOS ───────────────────────────────────────────────────────
INSERT INTO videos (title, description, video_type, video_url, sort_order) VALUES
('Factory Operation During COVID-19',
 'A look at how MAF Footwear Limited maintained operations safely during the COVID-19 pandemic with strict health protocols.',
 'upload',
 'videos/factory-covid-operation.mp4',
 1),
('MAF Shoes — Production Process Overview',
 'A complete walkthrough of our vertically integrated production process from raw material to finished shoes.',
 'youtube',
 'https://www.youtube.com/embed/dQw4w9WgXcQ',
 2);

-- ── JOBS ─────────────────────────────────────────────────────────
INSERT INTO jobs (title, department, location, job_type, description, requirements, responsibilities, deadline) VALUES
('Senior Production Manager',
 'Manufacturing',
 'Chandgaon Factory, Chittagong',
 'Full-time',
 'Lead production operations across multiple assembly lines ensuring quality targets, on-time delivery, and efficient use of resources.',
 'Minimum 8 years in footwear or garment manufacturing. Strong leadership skills. Knowledge of production planning and ERP systems required.',
 'Oversee daily production targets and line efficiency. Coordinate with planning, QC, and material teams. Implement lean manufacturing practices.',
 '2024-09-30'),

('Footwear Designer (CAD)',
 'Product Development',
 'Head Office / Factory, Chittagong',
 'Full-time',
 'Create innovative footwear designs from concept to sample-ready technical packs using CAD software.',
 'Degree in Footwear/Fashion/Industrial Design. Minimum 3 years experience. Proficiency in Rhino 3D or Shoe Master. Knowledge of footwear construction required.',
 'Develop 2D and 3D shoe designs. Prepare technical specifications and material callouts. Work with PDC team on samples.',
 '2024-08-31'),

('QC Inspector',
 'Quality Control',
 'Chandgaon Factory, Chittagong',
 'Full-time',
 'Conduct in-process and final inspections to ensure product quality meets customer specifications and AQL standards.',
 'Minimum 3 years in footwear QC. Knowledge of AQL sampling. Familiarity with BSCI/customer quality manuals preferred.',
 'Perform raw material, in-process, and final inspections. Maintain inspection records. Report defects and coordinate corrective actions.',
 '2024-08-15'),

('Export Documentation Officer',
 'Export & Logistics',
 'Head Office, Chittagong',
 'Full-time',
 'Prepare and manage all export documentation ensuring compliance with buyer requirements and customs regulations.',
 'Minimum 3 years in export documentation. Knowledge of LC, AWB, BL, customs procedures. English fluency essential.',
 'Prepare commercial invoice, packing list, certificate of origin, and other export documents. Liaise with freight forwarders and customs agents.',
 '2024-08-31');

-- ── TICKER ITEMS ─────────────────────────────────────────────────
INSERT INTO ticker_items (text, sort_order) VALUES
('100% Export Oriented Manufacturing',         1),
('Exporting to France, Spain, Brazil & Japan', 2),
('7 Vertical Production Units',                3),
('5000+ Skilled Workforce',                    4),
('BSCI Compliant Factory',                     5),
('EVA Foam to Finished Shoe — Under One Roof', 6);

-- ── PAGE META ────────────────────────────────────────────────────
INSERT INTO page_meta (page_slug, meta_title, meta_description) VALUES
('home',               'MAF Footwear Limited — 100% Export Oriented Shoe Manufacturing, Bangladesh', 'MAF Footwear Limited is a 100% export oriented quality athletic shoe, sandal, slipper and flip-flop manufacturing factory in Chittagong, Bangladesh. Exporting to France, Spain, Brazil, Japan and EU countries.'),
('company-overview',   'Company Overview — MAF Footwear Limited', 'Learn about MAF Footwear Limited — Bangladesh''s premier export-oriented footwear manufacturer with vertically integrated production units.'),
('company-profile',    'Company Profile — MAF Footwear Limited', 'Company profile, registration details, and key facts about MAF Footwear Limited.'),
('vision',             'Vision, Purpose & Core Values — MAF Footwear Limited', 'Our vision is to offer best in class sustainable footwear manufacturing. Our purpose: To Create Happiness Under Feet.'),
('board',              'Board of Directors — MAF Footwear Limited', 'Meet the Board of Directors of MAF Footwear Limited, guiding the company since 2007.'),
('production',         'Production Units — MAF Footwear Limited', 'MAF Footwear Limited operates 7 vertical production units: Athletic Shoes, Direct Injection, Flip-Flop, Rubber & PU Sole, IMEVA, EVA Foam, and Embroidery.'),
('operation',          'Operation Strength — MAF Footwear Limited', 'Our operation strengths include a skilled workforce, product development center, quality control, internal lab, compliance, and training center.'),
('products',           'Products — MAF Footwear Limited', 'Browse MAF Footwear Limited''s product catalog: Athletic Shoes, Casual Shoes, Flip Flops, Sandals, and Sports Footwear.'),
('news',               'News — MAF Footwear Limited', 'Latest news and announcements from MAF Footwear Limited.'),
('gallery',            'Gallery — MAF Footwear Limited', 'Photo gallery of MAF Footwear Limited factory, products, team, and events.'),
('videos',             'Videos — MAF Footwear Limited', 'Videos from MAF Footwear Limited including factory tours and production highlights.'),
('jobs',               'Jobs — MAF Footwear Limited', 'Career opportunities at MAF Footwear Limited. Join our team of 5000+ skilled professionals.'),
('contact',            'Contact — MAF Footwear Limited', 'Contact MAF Footwear Limited. Factory: Plot no- 296, Nasirabad I/A, Polytechnic Circle, Chattogram.');
