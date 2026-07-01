<?php
// Database Configuration — copy this to config.php and fill in your values
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_PORT', '3306');

// Site Configuration
define('SITE_NAME', 'Kimnest Containers');
define('SITE_URL', 'https://kimnestcontainers.co.ke');
define('SITE_EMAIL', 'info@kimnestcontainers.co.ke');
define('SITE_PHONE', '+254 XXX XXX XXX');
define('SITE_WHATSAPP', '254XXXXXXXXX');
define('BASE_URL', '');

// Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Admin Credentials
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', password_hash('admin123', PASSWORD_DEFAULT));

// Timezone
date_default_timezone_set('Africa/Nairobi');

// Load settings from DB if connected
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        if (!empty($settings['site_phone'])) define('SITE_PHONE', $settings['site_phone']);
        if (!empty($settings['site_email'])) define('SITE_EMAIL', $settings['site_email']);
        if (!empty($settings['site_whatsapp'])) define('SITE_WHATSAPP', $settings['site_whatsapp']);
        if (!empty($settings['site_name'])) define('SITE_NAME', $settings['site_name']);
    } catch (Exception $e) {}
}
