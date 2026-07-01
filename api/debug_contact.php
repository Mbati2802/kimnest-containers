<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

echo "=== Contact Handler Debug ===\n\n";

// Test 1: config.php
echo "1. Loading config.php... ";
try {
    require_once __DIR__ . '/../config.php';
    echo "OK (SITE_EMAIL=" . SITE_EMAIL . ")\n";
} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: functions.php
echo "2. Loading functions.php... ";
try {
    require_once __DIR__ . '/../includes/functions.php';
    echo "OK\n";
} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit;
}

// Test 3: DB connection
echo "3. Connecting to DB... ";
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "OK\n";
} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit;
}

// Test 4: site_settings table
echo "4. Querying site_settings... ";
try {
    $pdo->query("SELECT 1 FROM site_settings LIMIT 1");
    echo "OK\n";
} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}

// Test 5: notification_templates table
echo "5. Querying notification_templates... ";
try {
    $pdo->query("SELECT 1 FROM notification_templates LIMIT 1");
    echo "OK\n";
} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}

// Test 6: renderNotification
echo "6. Testing renderNotification... ";
try {
    $result = renderNotification('contact_notification', ['name' => 'Test', 'email' => 'test@test.com', 'phone' => '123', 'subject' => 'Test', 'message' => 'Hello', 'admin_url' => '/admin']);
    echo $result ? "OK (to: " . ($result['email'] ?: 'no email set') . ")" : "returned null\n";
} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}

// Test 7: mail function
echo "7. Testing mail()... ";
$testResult = @mail('test@test.com', 'Test', 'Test body');
echo $testResult ? "OK" : "FAILED (mail returned false)";
echo "\n";

echo "\n=== Done ===\n";
