<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Sanitize inputs
$full_name = sanitize($_POST['full_name'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

// Validate required fields
if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!isValidEmail($email)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

// Handle file upload
$attachment = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $attachment = uploadFile($_FILES['attachment']);
}

// Insert into database
try {
    $stmt = $pdo->prepare("INSERT INTO contacts (full_name, phone, email, subject, message, attachment) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $phone, $email, $subject, $message, $attachment]);
    
    // Send email notification to admin using template
    $notif = renderNotification('contact_notification', [
        'name'      => htmlspecialchars($full_name),
        'email'     => htmlspecialchars($email),
        'phone'     => htmlspecialchars($phone ?: 'Not provided'),
        'subject'   => htmlspecialchars($subject),
        'message'   => nl2br(htmlspecialchars($message)),
        'admin_url' => SITE_URL . '/admin/contacts.php',
    ]);
    if ($notif) {
        $toEmail = $notif['email'] ?: getSetting('notification_email', getSetting('site_email', SITE_EMAIL));
        sendMail($toEmail, $notif['subject'], $notif['body'], null, null, $email);
    }

    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent. We will get back to you shortly.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
