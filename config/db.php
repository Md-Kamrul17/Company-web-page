<?php
if (defined('MAF_DB_LOADED')) return;
define('MAF_DB_LOADED', true);

// ══════════════════════════════════════════════════════════
//  ⚠️  শুধু এই অংশ পরিবর্তন করুন
// ══════════════════════════════════════════════════════════
define('DB_HOST',    'localhost');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_NAME',    'maf_shoes');
define('DB_CHARSET', 'utf8mb4');

// ── SITE_URL auto-detect (http বা https যাই হোক কাজ করবে) ──
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$port     = $_SERVER['SERVER_PORT'] ?? 80;
$portStr  = ($port == 80 || $port == 443) ? '' : ':' . $port;
define('SITE_URL', $protocol . '://localhost' . $portStr . '/maf-website');

// ── নিচে পরিবর্তন করবেন না ────────────────────────────────
define('UPLOAD_DIR',    __DIR__ . '/../public/uploads/');
define('UPLOAD_URL',    SITE_URL . '/public/uploads/');
define('SESSION_NAME',  'maf_session');
define('ADMIN_SESSION', 'maf_admin');

class DB {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            $dsn = 'mysql:host=' . DB_HOST
                 . ';dbname=' . DB_NAME
                 . ';charset=' . DB_CHARSET;
            try {
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, array(
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ));
            } catch (PDOException $e) {
                http_response_code(500);
                die('
                <div style="font-family:Arial,sans-serif;max-width:600px;margin:60px auto;
                            padding:36px;border:1px solid #e0e0e0;border-top:4px solid #CC1E1E">
                  <h2 style="color:#CC1E1E;margin:0 0 16px">&#9888; Database সংযোগ ব্যর্থ</h2>
                  <p style="margin:0 0 16px;color:#444">
                    <strong>config/db.php</strong> ফাইলে DB_NAME, DB_USER, DB_PASS চেক করুন।
                  </p>
                  <div style="background:#fff3cd;padding:12px 16px;border-left:4px solid #f59e0b;
                              font-size:13px;color:#856404">
                    <strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '
                  </div>
                  <p style="font-size:12px;color:#888;margin-top:14px">
                    phpMyAdmin &#8594; <strong>maf_shoes</strong> database আছে কিনা দেখুন &#8594;
                    না থাকলে তৈরি করুন এবং <code>config/install.sql</code> import করুন।
                  </p>
                </div>');
            }
        }
        return self::$pdo;
    }

    public static function fetchAll($sql, $params = array()) {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function fetchOne($sql, $params = array()) {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return ($row !== false) ? $row : null;
    }

    public static function execute($sql, $params = array()) {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        $last = (int) self::connect()->lastInsertId();
        return ($last > 0) ? $last : $stmt->rowCount();
    }

    public static function lastId() {
        return (int) self::connect()->lastInsertId();
    }
}
