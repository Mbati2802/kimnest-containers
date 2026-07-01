<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$quoteCount = $pdo->query("SELECT COUNT(*) FROM quotes")->fetchColumn();
$contactCount = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$blogCount = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
$projectCount = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$newsletterCount = $pdo->query("SELECT COUNT(*) FROM newsletter WHERE status = 'active'")->fetchColumn();
$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

$recentQuotes = $pdo->query("SELECT * FROM quotes ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentContacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | <?= SITE_NAME ?> Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/index.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a>
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
                <a href="<?= BASE_URL ?>/admin/notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1>Dashboard</h1>
                    <div class="breadcrumb">Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></div>
                </div>
            </div>

            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Total Quotes</h4>
                        <div class="number"><?= $quoteCount ?></div>
                    </div>
                    <div class="stat-icon blue"><i class="fas fa-file-invoice"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Messages</h4>
                        <div class="number"><?= $contactCount ?></div>
                    </div>
                    <div class="stat-icon green"><i class="fas fa-envelope"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Blog Posts</h4>
                        <div class="number"><?= $blogCount ?></div>
                    </div>
                    <div class="stat-icon yellow"><i class="fas fa-newspaper"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Subscribers</h4>
                        <div class="number"><?= $newsletterCount ?></div>
                    </div>
                    <div class="stat-icon red"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Quotes</h3>
                        <a href="<?= BASE_URL ?>/admin/quotes.php" class="btn btn-link">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentQuotes)): ?>
                            <div class="empty-state"><p>No quotes yet.</p></div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr><th>Name</th><th>Type</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentQuotes as $quote): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($quote['full_name']) ?></td>
                                            <td><?= htmlspecialchars($quote['project_type'] ?: 'N/A') ?></td>
                                            <td><span class="badge badge-<?= $quote['status'] ?>"><?= ucfirst($quote['status']) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Recent Messages</h3>
                        <a href="<?= BASE_URL ?>/admin/contacts.php" class="btn btn-link">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentContacts)): ?>
                            <div class="empty-state"><p>No messages yet.</p></div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr><th>Name</th><th>Subject</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentContacts as $contact): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($contact['full_name']) ?></td>
                                            <td><?= htmlspecialchars(truncate($contact['subject'], 30)) ?></td>
                                            <td><span class="badge badge-<?= $contact['status'] ?>"><?= ucfirst($contact['status']) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
