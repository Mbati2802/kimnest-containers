<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = sanitize($_POST['status']);
    $stmt = $pdo->prepare("UPDATE quotes SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header('Location: ' . BASE_URL . '/admin/quotes.php?updated=1');
    exit;
}

$quotes = $pdo->query("SELECT * FROM quotes ORDER BY created_at DESC")->fetchAll();

$quote = null;
if (isset($_GET['view'])) {
    $id = (int)$_GET['view'];
    $stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = ?");
    $stmt->execute([$id]);
    $quote = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes | <?= SITE_NAME ?> Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/quotes.php" class="active"><i class="fas fa-file-invoice"></i> Quotes <span class="badge"><?= $newQuotes ?></span></a>
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
            <?php if ($quote): ?>
                <div class="page-header">
                    <h1>Quote #<?= $quote['id'] ?></h1>
                    <a href="<?= BASE_URL ?>/admin/quotes.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <div class="detail-view">
                    <div class="detail-row">
                        <div class="detail-field"><label>Full Name</label><p><?= htmlspecialchars($quote['full_name']) ?></p></div>
                        <div class="detail-field"><label>Company</label><p><?= htmlspecialchars($quote['company_name'] ?: 'N/A') ?></p></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field"><label>Phone</label><p><?= htmlspecialchars($quote['phone']) ?></p></div>
                        <div class="detail-field"><label>Email</label><p><?= htmlspecialchars($quote['email']) ?></p></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field"><label>Project Type</label><p><?= htmlspecialchars($quote['project_type'] ?: 'N/A') ?></p></div>
                        <div class="detail-field"><label>Container Size</label><p><?= htmlspecialchars($quote['container_size'] ?: 'N/A') ?></p></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field"><label>Quantity</label><p><?= htmlspecialchars($quote['quantity']) ?></p></div>
                        <div class="detail-field"><label>Project Location</label><p><?= htmlspecialchars($quote['project_location'] ?: 'N/A') ?></p></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field"><label>Budget</label><p><?= htmlspecialchars($quote['budget'] ?: 'N/A') ?></p></div>
                        <div class="detail-field"><label>Completion Date</label><p><?= htmlspecialchars($quote['completion_date'] ?: 'N/A') ?></p></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-field"><label>Intended Use</label><p><?= htmlspecialchars($quote['intended_use'] ?: 'N/A') ?></p></div>
                        <div class="detail-field"><label>Preferred Contact</label><p><?= ucfirst($quote['contact_method']) ?></p></div>
                    </div>
                    <?php if ($quote['description']): ?>
                        <div class="detail-field" style="margin-bottom:16px;"><label>Description</label><p><?= nl2br(htmlspecialchars($quote['description'])) ?></p></div>
                    <?php endif; ?>
                    <?php if ($quote['attachment']): ?>
                        <div class="detail-field" style="margin-bottom:16px;"><label>Attachment</label><p><a href="<?= BASE_URL ?>/<?= $quote['attachment'] ?>" target="_blank" class="btn btn-link">View File <i class="fas fa-external-link-alt"></i></a></p></div>
                    <?php endif; ?>
                    <div style="border-top:1px solid var(--admin-border);padding-top:16px;margin-top:16px;">
                        <form method="POST" style="display:flex;gap:12px;align-items:center;">
                            <input type="hidden" name="id" value="<?= $quote['id'] ?>">
                            <input type="hidden" name="update_status" value="1">
                            <label style="font-size:13px;font-weight:500;">Status:</label>
                            <select name="status" style="width:auto;">
                                <option value="new" <?= $quote['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                <option value="contacted" <?= $quote['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                                <option value="quoted" <?= $quote['status'] === 'quoted' ? 'selected' : '' ?>>Quoted</option>
                                <option value="closed" <?= $quote['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="page-header">
                    <h1>Quote Requests</h1>
                </div>

                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Status updated successfully.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <?php if (empty($quotes)): ?>
                            <div class="empty-state">
                                <i class="fas fa-file-invoice"></i>
                                <p>No quote requests yet.</p>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Project Type</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($quotes as $q): ?>
                                        <tr>
                                            <td>#<?= $q['id'] ?></td>
                                            <td><?= htmlspecialchars($q['full_name']) ?></td>
                                            <td><?= htmlspecialchars($q['phone']) ?></td>
                                            <td><?= htmlspecialchars($q['project_type'] ?: 'N/A') ?></td>
                                            <td><span class="badge badge-<?= $q['status'] ?>"><?= ucfirst($q['status']) ?></span></td>
                                            <td><?= formatDate($q['created_at']) ?></td>
                                            <td><a href="<?= BASE_URL ?>/admin/quotes.php?view=<?= $q['id'] ?>" class="btn btn-link btn-sm">View</a></td>
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
