<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Override config.php defaults with DB values (using globals since PHP constants can't be redefined)
try {
    $settingsStmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
    $dbSettings = $settingsStmt->fetchAll(PDO::FETCH_KEY_PAIR);
    if (!empty($dbSettings['site_phone'])) $GLOBALS['site_phone_db'] = $dbSettings['site_phone'];
    if (!empty($dbSettings['site_email'])) $GLOBALS['site_email_db'] = $dbSettings['site_email'];
    if (!empty($dbSettings['site_whatsapp'])) $GLOBALS['site_whatsapp_db'] = $dbSettings['site_whatsapp'];
    if (!empty($dbSettings['site_name'])) $GLOBALS['site_name_db'] = $dbSettings['site_name'];
} catch (Exception $e) {}

session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/admin/login');
        exit;
    }
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit;
}
