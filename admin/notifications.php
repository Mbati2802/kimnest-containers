<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if (isset($_GET['reset'])) {
    $key = $_GET['reset'];
    $defaults = [
        'contact_notification' => [
            'name' => 'Contact Form Submission',
            'subject' => 'New Contact: {subject}',
            'body' => '<h2>New Contact Message</h2><p><strong>From:</strong> {name}</p><p><strong>Email:</strong> {email}</p><p><strong>Phone:</strong> {phone}</p><p><strong>Subject:</strong> {subject}</p><p><strong>Message:</strong></p><p>{message}</p><hr><p><a href="{admin_url}">View in Admin Panel</a></p>',
            'placeholders' => 'name, email, phone, subject, message, admin_url',
        ],
        'quote_notification' => [
            'name' => 'Quote Request Submission',
            'subject' => 'New Quote Request from {name}',
            'body' => '<h2>New Quote Request</h2><p><strong>From:</strong> {name}</p><p><strong>Company:</strong> {company}</p><p><strong>Email:</strong> {email}</p><p><strong>Phone:</strong> {phone}</p><p><strong>Preferred Contact:</strong> {contact_method}</p><p><strong>Project Type:</strong> {project_type}</p><p><strong>Container Size:</strong> {container_size}</p><p><strong>Quantity:</strong> {quantity}</p><p><strong>Location:</strong> {location}</p><p><strong>Intended Use:</strong> {intended_use}</p><p><strong>Budget:</strong> {budget}</p><p><strong>Completion Date:</strong> {completion_date}</p><p><strong>Description:</strong></p><p>{description}</p>{cart_section}<hr><p><a href="{admin_url}">View in Admin Panel</a></p>',
            'placeholders' => 'name, company, email, phone, contact_method, project_type, container_size, quantity, location, intended_use, budget, completion_date, description, cart_section, admin_url',
        ],
    ];
    if (isset($defaults[$key])) {
        $d = $defaults[$key];
        $stmt = $pdo->prepare("UPDATE notification_templates SET subject=?, body=?, placeholders=? WHERE template_key=?");
        $stmt->execute([$d['subject'], $d['body'], $d['placeholders'], $key]);
    }
    header('Location: ' . BASE_URL . '/admin/notifications.php?reset_ok=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_email'])) {
        $notifEmail = sanitize($_POST['notification_email']);
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('notification_email', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$notifEmail, $notifEmail]);
        header('Location: ' . BASE_URL . '/admin/notifications.php?email_saved=1');
        exit;
    }
    $id = (int)$_POST['id'];
    $subject = sanitize($_POST['subject']);
    $body = $_POST['body'];
    $notifEmail = sanitize($_POST['notification_email'] ?? '');

    $stmt = $pdo->prepare("UPDATE notification_templates SET subject=?, body=?, notification_email=? WHERE id=?");
    $stmt->execute([$subject, $body, $notifEmail, $id]);
    header('Location: ' . BASE_URL . '/admin/notifications.php?saved=1');
    exit;
}

$templates = $pdo->query("SELECT * FROM notification_templates ORDER BY id ASC")->fetchAll();

$editTemplate = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE id = ?");
    $stmt->execute([$id]);
    $editTemplate = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Templates | <?= SITE_NAME ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <style>
        .placeholder-list { display:flex; flex-wrap:wrap; gap:6px; margin-top:6px; }
        .placeholder-tag { background:#e8f0fe; color:#3e7ac5; padding:3px 8px; border-radius:4px; font-size:11px; cursor:pointer; font-family:monospace; }
        .placeholder-tag:hover { background:#3e7ac5; color:#fff; }
        .template-note { font-size:12px; color:#6b7280; margin-top:4px; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="logo">Kim<span>nest</span></div>
                <div class="subtitle">Admin Panel</div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">Main</div>
                <a href="<?= BASE_URL ?>/admin/index.php"><i class="fas fa-th-large"></i> Dashboard</a>
                <div class="nav-section">Content</div>
                <a href="<?= BASE_URL ?>/admin/content.php"><i class="fas fa-edit"></i> Site Content</a>
                <a href="<?= BASE_URL ?>/admin/settings.php"><i class="fas fa-cog"></i> Site Settings</a>
                <a href="<?= BASE_URL ?>/admin/media.php"><i class="fas fa-photo-video"></i> Media</a>
                <div class="nav-section">Manage</div>
                <a href="<?= BASE_URL ?>/admin/quotes.php"><i class="fas fa-file-invoice"></i> Quotes <span class="badge"><?= $newQuotes ?></span></a>
                <a href="<?= BASE_URL ?>/admin/contacts.php"><i class="fas fa-envelope"></i> Contacts <span class="badge"><?= $unreadContacts ?></span></a>
                <a href="<?= BASE_URL ?>/admin/blog.php"><i class="fas fa-newspaper"></i> Blog Posts</a>
                <a href="<?= BASE_URL ?>/admin/products.php"><i class="fas fa-box"></i> Products</a>
                <a href="<?= BASE_URL ?>/admin/projects.php"><i class="fas fa-images"></i> Projects</a>
                <a href="<?= BASE_URL ?>/admin/newsletter.php"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/admin/notifications.php" class="active"><i class="fas fa-bell"></i> Notifications</a>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php if ($editTemplate): ?>
                <div class="page-header">
                    <h1>Edit: <?= htmlspecialchars($editTemplate['name']) ?></h1>
                    <a href="<?= BASE_URL ?>/admin/notifications.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <?php if (isset($_GET['saved'])): ?>
                    <div class="alert alert-success">Template saved successfully.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" onsubmit="document.getElementById('quillHtml').value = quill.root.innerHTML;">
                            <input type="hidden" name="id" value="<?= $editTemplate['id'] ?>">

                            <div class="form-group">
                                <label>Template Name</label>
                                <input type="text" value="<?= htmlspecialchars($editTemplate['name']) ?>" disabled style="background:#f3f4f6;">
                            </div>

                            <div class="form-group">
                                <label>Email Subject Line</label>
                                <input type="text" name="subject" value="<?= htmlspecialchars($editTemplate['subject']) ?>" required>
                                <div class="template-note">Use <code>{placeholder}</code> to insert dynamic values.</div>
                            </div>

                            <div class="form-group">
                                <label>Send Notifications To</label>
                                <input type="email" name="notification_email" value="<?= htmlspecialchars($editTemplate['notification_email'] ?? '') ?>" placeholder="<?= htmlspecialchars(SITE_EMAIL) ?>">
                                <div class="template-note">Email that receives this notification. Leave empty to use the site default email.</div>
                            </div>

                            <div class="form-group">
                                <label>Email Body (HTML)</label>
                                <div id="quillEditor" style="height:350px;margin-bottom:12px;"></div>
                                <textarea name="body" id="quillHtml" style="display:none;"><?= htmlspecialchars($editTemplate['body']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Available Placeholders</label>
                                <p class="template-note">Click a placeholder to copy it to your clipboard, then paste it into the subject or body.</p>
                                <div class="placeholder-list" id="placeholderList">
                                    <?php foreach (explode(',', $editTemplate['placeholders']) as $ph): ?>
                                        <span class="placeholder-tag" onclick="navigator.clipboard.writeText('{<?= trim($ph) ?>}').then(()=>this.textContent='Copied!');setTimeout(()=>this.textContent='{<?= trim($ph) ?>}',1200)">{<?= trim($ph) ?>}</span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Template</button>
                            <a href="<?= BASE_URL ?>/admin/notifications.php?reset=<?= urlencode($editTemplate['template_key']) ?>" class="btn btn-outline" onclick="return confirm('Reset this template to default?')"><i class="fas fa-undo"></i> Reset to Default</a>
                        </form>
                    </div>
                </div>

                <script>
                var quill = new Quill('#quillEditor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1,2,3,false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Compose your email notification template...'
                });
                quill.root.innerHTML = <?= json_encode($editTemplate['body']) ?>;
                </script>
            <?php else: ?>
                <div class="page-header">
                    <h1>Notification Settings</h1>
                </div>

                <?php if (isset($_GET['email_saved'])): ?>
                    <div class="alert alert-success">Notification email updated.</div>
                <?php elseif (isset($_GET['saved'])): ?>
                    <div class="alert alert-success">Template saved successfully.</div>
                <?php elseif (isset($_GET['reset_ok'])): ?>
                    <div class="alert alert-success">Template reset to default.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <p style="margin-bottom:16px;font-size:13px;color:#6b7280;">Customize the email notifications sent to admin when a contact message or quote request is submitted.</p>
                        <?php if (empty($templates)): ?>
                            <div class="empty-state">
                                <i class="fas fa-bell"></i>
                                <p>No notification templates found. Run the database migration to add them.</p>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Template</th>
                                        <th>Subject</th>
                                        <th>Notification Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($templates as $t): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($t['name']) ?></strong><br><small style="color:#6b7280;"><?= htmlspecialchars($t['template_key']) ?></small></td>
                                            <td><?= htmlspecialchars($t['subject']) ?></td>
                                            <td><small style="color:#6b7280;"><?= $t['notification_email'] ? htmlspecialchars($t['notification_email']) : '<em>site default</em>' ?></small></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/admin/notifications.php?edit=<?= $t['id'] ?>" class="btn btn-link btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
