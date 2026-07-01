<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM newsletter WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/newsletter.php?deleted=1');
    exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE newsletter SET status = IF(status='active','unsubscribed','active') WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/newsletter.php?toggled=1');
    exit;
}

// Send broadcast
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_campaign'])) {
    $subject = sanitize($_POST['subject'] ?? '');
    $body = $_POST['body'] ?? '';
    if (empty($subject) || empty($body)) {
        $error = 'Subject and message body are required.';
    } else {
        $subscribers = $pdo->query("SELECT id, email, name FROM newsletter WHERE status = 'active'")->fetchAll();
        $total = count($subscribers);
        $sent = 0;

        $stmt = $pdo->prepare("INSERT INTO newsletter_campaigns (subject, body, total_count, status) VALUES (?, ?, ?, 'sending')");
        $stmt->execute([$subject, $body, $total]);
        $campaignId = $pdo->lastInsertId();

        $siteName = getSetting('site_name', SITE_NAME);

        foreach ($subscribers as $sub) {
            $unsubLink = BASE_URL . '/api/unsubscribe.php?email=' . urlencode($sub['email']);
            $emailBody = "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>body{font-family:Arial,sans-serif;color:#333;line-height:1.6;margin:0;padding:0}.container{max-width:600px;margin:0 auto;padding:20px}.header{background:#3e7ac5;color:#fff;padding:20px;text-align:center;border-radius:6px 6px 0 0}.header h1{margin:0;font-size:20px}.content{padding:20px;background:#fff;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 6px 6px}.footer{text-align:center;padding:16px;font-size:11px;color:#9ca3af}a{color:#3e7ac5}</style></head><body><div class='container'><div class='header'><h1>" . htmlspecialchars($subject) . "</h1></div><div class='content'>" . $body . "</div><div class='footer'><p>You're receiving this because you subscribed to $siteName.</p><p><a href='$unsubLink'>Unsubscribe</a></p></div></div></body></html>";

            if (sendMail($sub['email'], $subject, $emailBody)) {
                $sent++;
            }
        }

        $stmt = $pdo->prepare("UPDATE newsletter_campaigns SET sent_count = ?, status = ? WHERE id = ?");
        $stmt->execute([$sent, $sent === $total ? 'sent' : 'failed', $campaignId]);

        $msg = "Campaign sent to $sent of $total subscribers.";
        header('Location: ' . BASE_URL . '/admin/newsletter.php?sent=' . urlencode($msg));
        exit;
    }
}

$subscribers = $pdo->query("SELECT * FROM newsletter ORDER BY created_at DESC")->fetchAll();
$activeCount = $pdo->query("SELECT COUNT(*) FROM newsletter WHERE status = 'active'")->fetchColumn();
$unsubscribedCount = $pdo->query("SELECT COUNT(*) FROM newsletter WHERE status = 'unsubscribed'")->fetchColumn();
$totalCount = count($subscribers);
$campaigns = $pdo->query("SELECT * FROM newsletter_campaigns ORDER BY created_at DESC LIMIT 20")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter | <?= SITE_NAME ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
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
                <a href="<?= BASE_URL ?>/admin/newsletter.php" class="active"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/admin/notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Newsletter</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Subscriber removed.</div>
            <?php elseif (isset($_GET['toggled'])): ?>
                <div class="alert alert-success">Subscriber status updated.</div>
            <?php elseif (isset($_GET['sent'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['sent']) ?></div>
            <?php endif; ?>

            <div class="newsletter-stats">
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Total Subscribers</h4>
                        <div class="number"><?= $totalCount ?></div>
                    </div>
                    <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Active</h4>
                        <div class="number"><?= $activeCount ?></div>
                    </div>
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Unsubscribed</h4>
                        <div class="number"><?= $unsubscribedCount ?></div>
                    </div>
                    <div class="stat-icon yellow"><i class="fas fa-user-slash"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Campaigns Sent</h4>
                        <div class="number"><?= count($campaigns) ?></div>
                    </div>
                    <div class="stat-icon purple"><i class="fas fa-history"></i></div>
                </div>
            </div>

            <?php if ($activeCount > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h3>Compose Broadcast</h3>
                    <span style="font-size:13px;color:var(--admin-text-muted);">Sending to <strong><?= $activeCount ?></strong> active subscribers</span>
                </div>
                <div class="card-body">
                    <form method="POST" onsubmit="document.getElementById('quillHtml').value = quill.root.innerHTML; return confirm('Send this broadcast to <?= $activeCount ?> subscribers?')">
                        <div class="form-group">
                            <label>Subject *</label>
                            <input type="text" name="subject" placeholder="Email subject line" required style="width:100%;padding:10px;border:1px solid var(--admin-border);border-radius:4px;font-size:14px;">
                        </div>
                        <div class="form-group">
                            <label>Message Body *</label>
                            <div id="quillEditor" style="height:300px;margin-bottom:12px;"></div>
                            <textarea name="body" id="quillHtml" style="display:none;"></textarea>
                        </div>
                        <button type="submit" name="send_campaign" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send to All Active Subscribers</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($campaigns)): ?>
            <div class="card">
                <div class="card-header">
                    <h3>Campaign History</h3>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Sent / Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($campaigns as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars(mb_substr($c['subject'], 0, 50)) ?></td>
                                    <td><?= $c['sent_count'] ?> / <?= $c['total_count'] ?></td>
                                    <td><span class="badge badge-<?= $c['status'] === 'sent' ? 'success' : ($c['status'] === 'failed' ? 'danger' : 'warning') ?>"><?= ucfirst($c['status']) ?></span></td>
                                    <td><?= formatDate($c['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3>Subscribers</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($subscribers)): ?>
                        <div class="empty-state">
                            <i class="fas fa-paper-plane"></i>
                            <p>No subscribers yet.</p>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Subscribed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subscribers as $sub): ?>
                                    <tr>
                                        <td>#<?= $sub['id'] ?></td>
                                        <td><?= htmlspecialchars($sub['name'] ?: '-') ?></td>
                                        <td><?= htmlspecialchars($sub['email']) ?></td>
                                        <td><span class="badge badge-<?= $sub['status'] ?>"><?= ucfirst($sub['status']) ?></span></td>
                                        <td><?= formatDate($sub['created_at']) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/newsletter.php?toggle=<?= $sub['id'] ?>" class="btn btn-link btn-sm"><i class="fas fa-toggle-on"></i></a>
                                            <a href="<?= BASE_URL ?>/admin/newsletter.php?delete=<?= $sub['id'] ?>" class="btn btn-link btn-sm" onclick="return confirm('Remove this subscriber?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>
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
        placeholder: 'Write your newsletter content here...'
    });
    </script>
</body>
</html>
