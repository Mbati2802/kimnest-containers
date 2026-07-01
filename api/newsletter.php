<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = sanitize($_POST['email'] ?? '');

if (empty($email) || !isValidEmail($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    // Check if already subscribed
    $stmt = $pdo->prepare("SELECT id, status FROM newsletter WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        if ($existing['status'] === 'active') {
            echo json_encode(['success' => false, 'message' => 'You are already subscribed!']);
            exit;
        } else {
            // Reactivate subscription
            $stmt = $pdo->prepare("UPDATE newsletter SET status = 'active' WHERE id = ?");
            $stmt->execute([$existing['id']]);
        }
    } else {
        // New subscription
        $stmt = $pdo->prepare("INSERT INTO newsletter (email) VALUES (?)");
        $stmt->execute([$email]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Thank you for subscribing to our newsletter!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
