<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/blog.php?deleted=1');
    exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE blog_posts SET status = IF(status='published','draft','published') WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/blog.php?toggled=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $title = sanitize($_POST['title']);
    $slug = slugify($title);
    $excerpt = sanitize($_POST['excerpt']);
    $content = $_POST['content'];
    $category = sanitize($_POST['category']);
    $status = sanitize($_POST['status']);

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE blog_posts SET title=?, slug=?, excerpt=?, content=?, category=?, status=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $content, $category, $status, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, excerpt, content, category, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $category, $status]);
    }
    header('Location: ' . BASE_URL . '/admin/blog.php?saved=1');
    exit;
}

$posts = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC")->fetchAll();

$editPost = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$id]);
    $editPost = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | <?= SITE_NAME ?> Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/blog.php" class="active"><i class="fas fa-newspaper"></i> Blog Posts</a>
                <a href="<?= BASE_URL ?>/admin/products.php"><i class="fas fa-box"></i> Products</a>
                <a href="<?= BASE_URL ?>/admin/projects.php"><i class="fas fa-images"></i> Projects</a>
                <a href="<?= BASE_URL ?>/admin/newsletter.php"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php if ($editPost || isset($_GET['new'])): ?>
                <div class="page-header">
                    <h1><?= $editPost ? 'Edit Post' : 'New Post' ?></h1>
                    <a href="<?= BASE_URL ?>/admin/blog.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $editPost['id'] ?? '' ?>">

                            <div class="form-group">
                                <label>Title *</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($editPost['title'] ?? '') ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Category</label>
                                    <input type="text" name="category" value="<?= htmlspecialchars($editPost['category'] ?? '') ?>" placeholder="e.g. Container Homes">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status">
                                        <option value="draft" <?= ($editPost['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="published" <?= ($editPost['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Excerpt</label>
                                <textarea name="excerpt" rows="3"><?= htmlspecialchars($editPost['excerpt'] ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Content *</label>
                                <textarea name="content" rows="12" required><?= htmlspecialchars($editPost['content'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Post</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="page-header">
                    <h1>Blog Posts</h1>
                    <a href="<?= BASE_URL ?>/admin/blog.php?new=1" class="btn btn-primary"><i class="fas fa-plus"></i> New Post</a>
                </div>

                <?php if (isset($_GET['saved'])): ?>
                    <div class="alert alert-success">Post saved successfully.</div>
                <?php elseif (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Post deleted.</div>
                <?php elseif (isset($_GET['toggled'])): ?>
                    <div class="alert alert-success">Post status updated.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <?php if (empty($posts)): ?>
                            <div class="empty-state">
                                <i class="fas fa-newspaper"></i>
                                <p>No blog posts yet. <a href="<?= BASE_URL ?>/admin/blog.php?new=1">Create one</a></p>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($posts as $post): ?>
                                        <tr>
                                            <td>#<?= $post['id'] ?></td>
                                            <td><?= htmlspecialchars($post['title']) ?></td>
                                            <td><?= htmlspecialchars($post['category'] ?: '-') ?></td>
                                            <td><span class="badge badge-<?= $post['status'] ?>"><?= ucfirst($post['status']) ?></span></td>
                                            <td><?= formatDate($post['created_at']) ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/admin/blog.php?edit=<?= $post['id'] ?>" class="btn btn-link btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="<?= BASE_URL ?>/admin/blog.php?toggle=<?= $post['id'] ?>" class="btn btn-link btn-sm"><i class="fas fa-toggle-on"></i></a>
                                                <a href="<?= BASE_URL ?>/admin/blog.php?delete=<?= $post['id'] ?>" class="btn btn-link btn-sm" onclick="return confirm('Delete this post?')"><i class="fas fa-trash"></i></a>
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
