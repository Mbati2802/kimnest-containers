<?php
require_once __DIR__ . '/../includes/db.php';

$email = isset($_GET['email']) ? sanitize($_GET['email']) : '';

if (!empty($email)) {
    $stmt = $pdo->prepare("UPDATE newsletter SET status = 'unsubscribed' WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed | <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Lato',Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#f3f4f6;}
        .card{background:#fff;padding:40px;border-radius:12px;text-align:center;max-width:400px;box-shadow:0 4px 20px rgba(0,0,0,0.08);}
        .icon{font-size:48px;color:#3e7ac5;margin-bottom:16px;}
        h1{font-size:20px;margin-bottom:8px;color:#1a1d23;}
        p{font-size:14px;color:#6b7280;line-height:1.6;}
        a{color:#3e7ac5;text-decoration:none;}
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">&#10003;</div>
        <h1>You've Been Unsubscribed</h1>
        <p>You have been successfully unsubscribed from our newsletter. You will no longer receive email communications from us.</p>
        <p><a href="<?= BASE_URL ?>/">Back to Home</a></p>
    </div>
</body>
</html>
