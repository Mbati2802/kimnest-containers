<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/faqs.php?deleted=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $category = sanitize($_POST['category']);
    $question = sanitize($_POST['question']);
    $answer = $_POST['answer'];
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE faqs SET category=?, question=?, answer=?, sort_order=? WHERE id=?");
        $stmt->execute([$category, $question, $answer, $sort_order, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO faqs (category, question, answer, sort_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$category, $question, $answer, $sort_order]);
    }
    header('Location: ' . BASE_URL . '/admin/faqs.php?saved=1');
    exit;
}

$faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC, id ASC")->fetchAll();

$editFaq = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->execute([$id]);
    $editFaq = $stmt->fetch();
}

$categories = $pdo->query("SELECT DISTINCT category FROM faqs ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
$categories = array_merge($categories, ['General', 'Fabrication', 'Design & Planning', 'Pricing', 'Delivery', 'Other']);
$categories = array_unique($categories);
sort($categories);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs | <?= SITE_NAME ?> Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/contacts.php"><i class="fas fa-envelope"></i> Contacts <span class="badge"><?= $unreadContacts ?></span></a>
                <a href="<?= BASE_URL ?>/admin/blog.php"><i class="fas fa-newspaper"></i> Blog Posts</a>
                <a href="<?= BASE_URL ?>/admin/products.php"><i class="fas fa-box"></i> Products</a>
                <a href="<?= BASE_URL ?>/admin/projects.php"><i class="fas fa-images"></i> Projects</a>
                <a href="<?= BASE_URL ?>/admin/newsletter.php"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php" class="active"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/admin/notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php if ($editFaq || isset($_GET['new'])): ?>
                <div class="page-header">
                    <h1><?= $editFaq ? 'Edit FAQ' : 'New FAQ' ?></h1>
                    <a href="<?= BASE_URL ?>/admin/faqs.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $editFaq['id'] ?? '' ?>">

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= htmlspecialchars($cat) ?>" <?= ($editFaq['category'] ?? '') === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sort Order</label>
                                    <input type="number" name="sort_order" value="<?= $editFaq['sort_order'] ?? 0 ?>" min="0">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Question *</label>
                                <input type="text" name="question" value="<?= htmlspecialchars($editFaq['question'] ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Answer *</label>
                                <textarea name="answer" rows="5" required><?= htmlspecialchars($editFaq['answer'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save FAQ</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="page-header">
                    <h1>FAQs</h1>
                    <a href="<?= BASE_URL ?>/admin/faqs.php?new=1" class="btn btn-primary"><i class="fas fa-plus"></i> New FAQ</a>
                </div>

                <?php if (isset($_GET['saved'])): ?>
                    <div class="alert alert-success">FAQ saved successfully.</div>
                <?php elseif (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">FAQ deleted.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <?php if (empty($faqs)): ?>
                            <div class="empty-state">
                                <i class="fas fa-question-circle"></i>
                                <p>No FAQs yet. <a href="<?= BASE_URL ?>/admin/faqs.php?new=1">Add one</a></p>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Category</th>
                                        <th>Question</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($faqs as $f): ?>
                                        <tr>
                                            <td><?= $f['sort_order'] ?></td>
                                            <td><span class="badge badge--<?= strtolower(str_replace(' ', '-', $f['category'])) ?>"><?= htmlspecialchars($f['category']) ?></span></td>
                                            <td><?= htmlspecialchars(mb_substr($f['question'], 0, 60)) ?><?= mb_strlen($f['question']) > 60 ? '...' : '' ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/admin/faqs.php?edit=<?= $f['id'] ?>" class="btn btn-link btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="<?= BASE_URL ?>/admin/faqs.php?delete=<?= $f['id'] ?>" class="btn btn-link btn-sm" onclick="return confirm('Delete this FAQ?')"><i class="fas fa-trash"></i></a>
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
