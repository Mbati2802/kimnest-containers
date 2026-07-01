<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Sanitize inputs
$full_name = sanitize($_POST['full_name'] ?? '');
$company_name = sanitize($_POST['company_name'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$project_type = sanitize($_POST['project_type'] ?? '');
$container_size = sanitize($_POST['container_size'] ?? '');
$quantity = (int)($_POST['quantity'] ?? 1);
$project_location = sanitize($_POST['project_location'] ?? '');
$intended_use = sanitize($_POST['intended_use'] ?? '');
$budget = sanitize($_POST['budget'] ?? '');
$completion_date = sanitize($_POST['completion_date'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$contact_method = sanitize($_POST['contact_method'] ?? 'phone');
$cart_data = sanitize($_POST['cart_data'] ?? '');

// Validate required fields
if (empty($full_name) || empty($phone) || empty($email)) {
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
    if (!$attachment) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file. Please try again.']);
        exit;
    }
}

// Insert into database
try {
    $stmt = $pdo->prepare("INSERT INTO quotes (full_name, company_name, phone, email, project_type, container_size, quantity, project_location, intended_use, budget, completion_date, description, attachment, contact_method, cart_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $full_name, $company_name, $phone, $email, $project_type, $container_size,
        $quantity, $project_location, $intended_use, $budget, $completion_date,
        $description, $attachment, $contact_method, !empty($cart_data) ? $cart_data : null
    ]);

    // Clear cart if cart data was submitted
    if (!empty($cart_data) && isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }

    // Build cart section for template
    $cartSection = '';
    if (!empty($cart_data)) {
        $cartItems = json_decode($cart_data, true);
        if (is_array($cartItems)) {
            $cartSection = '<h3>Cart Items:</h3><ul>';
            foreach ($cartItems as $item) {
                $cartSection .= '<li>' . htmlspecialchars($item['name'] ?? 'Product');
                if (!empty($item['size'])) $cartSection .= ' - ' . htmlspecialchars($item['size']);
                if (!empty($item['qty'])) $cartSection .= ' x ' . (int)$item['qty'];
                $cartSection .= '</li>';
            }
            $cartSection .= '</ul>';
        }
    }

    // Send email notification to admin using template
    $adminEmail = getSetting('site_email', SITE_EMAIL);
    $notif = renderNotification('quote_notification', [
        'name'            => htmlspecialchars($full_name),
        'company'         => htmlspecialchars($company_name ?: 'N/A'),
        'email'           => htmlspecialchars($email),
        'phone'           => htmlspecialchars($phone),
        'contact_method'  => htmlspecialchars($contact_method),
        'project_type'    => htmlspecialchars($project_type ?: 'N/A'),
        'container_size'  => htmlspecialchars($container_size ?: 'N/A'),
        'quantity'        => $quantity,
        'location'        => htmlspecialchars($project_location ?: 'N/A'),
        'intended_use'    => htmlspecialchars($intended_use ?: 'N/A'),
        'budget'          => htmlspecialchars($budget ?: 'N/A'),
        'completion_date' => htmlspecialchars($completion_date ?: 'N/A'),
        'description'     => nl2br(htmlspecialchars($description ?: 'N/A')),
        'cart_section'    => $cartSection,
        'admin_url'       => SITE_URL . '/admin/quotes.php',
    ]);
    if ($notif) {
        sendMail($adminEmail, $notif['subject'], $notif['body'], null, null, $email);
    }

    $response = ['success' => true, 'message' => 'Thank you! Your quote request has been submitted. We will contact you shortly.'];
    if (!empty($cart_data)) {
        $response['redirect'] = BASE_URL . '/request-quote?thank_you=1';
    }
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
