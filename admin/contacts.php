<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = sanitize($_POST['status']);
    $stmt = $pdo->prepare("UPDATE contacts SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header('Location: ' . BASE_URL . '/admin/contacts.php?updated=1');
    exit;
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();

$contact = null;
if (isset($_GET['view'])) {
    $id = (int)$_GET['view'];
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    $contact = $stmt->fetch();

    if ($contact && $contact['status'] === 'unread') {
        $stmt = $pdo->prepare("UPDATE contacts SET status = 'read' WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts | <?= SITE_NAME ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
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
                <a href="<?= BASE_URL ?>/admin/contacts.php" class="active"><i class="fas fa-envelope"></i> Contacts <span class="badge"><?= $unreadContacts ?></span></a>
                <a href="<?= BASE_URL ?>/admin/blog.php"><i class="fas fa-newspaper"></i> Blog Posts</a>
                <a href="<?= BASE_URL ?>/admin/products.php"><i class="fas fa-box"></i> Products</a>
                <a href="<?= BASE_URL ?>/admin/projects.php"><i class="fas fa-images"></i> Projects</a>
                <a href="<?= BASE_URL ?>/admin/newsletter.php"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/admin/notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php if ($contact): ?>
                <div class="page-header">
                    <h1>Message #<?= $contact['id'] ?></h1>
                    <a href="<?= BASE_URL ?>/admin/contacts.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <div class="detail-view">
                    <div class="detail-row">
                        <div class="detail-field"><label>Full Name</label><p><?= htmlspecialchars($contact['full_name']) ?></p></div>
                        <div class="detail-field"><label>Phone</label><p><?= htmlspecialchars($contact['phone'] ?: 'N/A') ?></p></div>
                    </div>
                    <div class="detail-field" style="margin-bottom:16px;">
                        <label>Email</label>
                        <p><a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a></p>
                    </div>
                    <div class="detail-field" style="margin-bottom:16px;">
                        <label>Subject</label>
                        <p><?= htmlspecialchars($contact['subject']) ?></p>
                    </div>
                    <div class="detail-field" style="margin-bottom:16px;">
                        <label>Message</label>
                        <p><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
                    </div>
                    <?php if ($contact['attachment']): ?>
                        <div class="detail-field" style="margin-bottom:16px;">
                            <label>Attachment</label>
                            <p><a href="<?= BASE_URL ?>/<?= $contact['attachment'] ?>" target="_blank" class="btn btn-link">View File <i class="fas fa-external-link-alt"></i></a></p>
                        </div>
                    <?php endif; ?>
                    <div class="detail-field" style="margin-bottom:16px;">
                        <label>Date</label>
                        <p><?= formatDate($contact['created_at'], 'M d, Y h:i A') ?></p>
                    </div>
                    <div style="border-top:1px solid var(--admin-border);padding-top:16px;margin-top:16px;">
                        <form method="POST" style="display:flex;gap:12px;align-items:center;">
                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                            <input type="hidden" name="update_status" value="1">
                            <label style="font-size:13px;font-weight:500;">Status:</label>
                            <select name="status" style="width:auto;">
                                <option value="unread" <?= $contact['status'] === 'unread' ? 'selected' : '' ?>>Unread</option>
                                <option value="read" <?= $contact['status'] === 'read' ? 'selected' : '' ?>>Read</option>
                                <option value="replied" <?= $contact['status'] === 'replied' ? 'selected' : '' ?>>Replied</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="page-header">
                    <h1>Contact Messages</h1>
                </div>

                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Status updated successfully.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <?php if (empty($contacts)): ?>
                            <div class="empty-state">
                                <i class="fas fa-envelope"></i>
                                <p>No messages yet.</p>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contacts as $c): ?>
                                        <tr>
                                            <td>#<?= $c['id'] ?></td>
                                            <td><?= htmlspecialchars($c['full_name']) ?></td>
                                            <td><?= htmlspecialchars($c['email']) ?></td>
                                            <td><?= htmlspecialchars(truncate($c['subject'], 30)) ?></td>
                                            <td><span class="badge badge-<?= $c['status'] ?>"><?= ucfirst($c['status']) ?></span></td>
                                            <td><?= formatDate($c['created_at']) ?></td>
                                            <td><a href="<?= BASE_URL ?>/admin/contacts.php?view=<?= $c['id'] ?>" class="btn btn-link btn-sm">View</a></td>
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
