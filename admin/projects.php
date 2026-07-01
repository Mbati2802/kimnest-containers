<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/projects.php?deleted=1');
    exit;
}

if (isset($_GET['toggle_featured'])) {
    $id = (int)$_GET['toggle_featured'];
    $stmt = $pdo->prepare("UPDATE projects SET featured = NOT featured WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/projects.php?toggled=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $title = sanitize($_POST['title']);
    $slug = slugify($title);
    $category = sanitize($_POST['category']);
    $description = sanitize($_POST['description']);
    $full_description = $_POST['full_description'];
    $location = sanitize($_POST['location']);
    $client_name = sanitize($_POST['client_name']);
    $client_testimonial = sanitize($_POST['client_testimonial']);
    $completed_date = $_POST['completed_date'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE projects SET title=?, slug=?, category=?, description=?, full_description=?, location=?, client_name=?, client_testimonial=?, completed_date=?, featured=? WHERE id=?");
        $stmt->execute([$title, $slug, $category, $description, $full_description, $location, $client_name, $client_testimonial, $completed_date, $featured, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO projects (title, slug, category, description, full_description, location, client_name, client_testimonial, completed_date, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $description, $full_description, $location, $client_name, $client_testimonial, $completed_date, $featured]);
        $id = $pdo->lastInsertId();
    }

    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $image = uploadFile(['tmp_name' => $tmp_name, 'name' => $_FILES['images']['name'][$key]]);
                if ($image) {
                    $stmt = $pdo->prepare("INSERT INTO project_images (project_id, image) VALUES (?, ?)");
                    $stmt->execute([$id, $image]);
                }
            }
        }
    }

    header('Location: ' . BASE_URL . '/admin/projects.php?saved=1');
    exit;
}

$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();

$editProject = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $editProject = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects | <?= SITE_NAME ?> Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/projects.php" class="active"><i class="fas fa-images"></i> Projects</a>
                <a href="<?= BASE_URL ?>/admin/newsletter.php"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/admin/notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <?php if ($editProject || isset($_GET['new'])): ?>
                <div class="page-header">
                    <h1><?= $editProject ? 'Edit Project' : 'New Project' ?></h1>
                    <a href="<?= BASE_URL ?>/admin/projects.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $editProject['id'] ?? '' ?>">

                            <div class="form-group">
                                <label>Title *</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($editProject['title'] ?? '') ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category" required>
                                        <option value="">Select Category</option>
                                        <?php foreach (['Residential', 'Office', 'Retail', 'Restaurant', 'Educational', 'Healthcare', 'Other'] as $cat): ?>
                                            <option value="<?= $cat ?>" <?= ($editProject['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Completed Date</label>
                                    <input type="date" name="completed_date" value="<?= $editProject['completed_date'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location" value="<?= htmlspecialchars($editProject['location'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Client Name</label>
                                    <input type="text" name="client_name" value="<?= htmlspecialchars($editProject['client_name'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Short Description</label>
                                <textarea name="description" rows="3"><?= htmlspecialchars($editProject['description'] ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Full Description</label>
                                <textarea name="full_description" rows="6"><?= htmlspecialchars($editProject['full_description'] ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Client Testimonial</label>
                                <textarea name="client_testimonial" rows="3"><?= htmlspecialchars($editProject['client_testimonial'] ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                    <input type="checkbox" name="featured" value="1" <?= ($editProject['featured'] ?? 0) ? 'checked' : '' ?>>
                                    Featured Project
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Project Images</label>
                                <input type="file" name="images[]" multiple accept="image/*">
                                <div class="hint">JPG, PNG accepted. Select multiple files. <strong>Recommended: 800×600px</strong> for optimal display.</div>
                            </div>

                            <?php if ($editProject): ?>
                                <?php $images = getProjectImages($editProject['id']); ?>
                                <?php if (!empty($images)): ?>
                                    <div class="form-group">
                                        <label>Current Images</label>
                                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                            <?php foreach ($images as $img): ?>
                                                <img src="<?= BASE_URL ?>/<?= $img['image'] ?>" style="width:80px;height:80px;object-fit:cover;border-radius:6px;border:1px solid var(--admin-border);">
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Project</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="page-header">
                    <h1>Projects</h1>
                    <a href="<?= BASE_URL ?>/admin/projects.php?new=1" class="btn btn-primary"><i class="fas fa-plus"></i> New Project</a>
                </div>

                <?php if (isset($_GET['saved'])): ?>
                    <div class="alert alert-success">Project saved successfully.</div>
                <?php elseif (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Project deleted.</div>
                <?php elseif (isset($_GET['toggled'])): ?>
                    <div class="alert alert-success">Featured status updated.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <?php if (empty($projects)): ?>
                            <div class="empty-state">
                                <i class="fas fa-images"></i>
                                <p>No projects yet. <a href="<?= BASE_URL ?>/admin/projects.php?new=1">Add one</a></p>
                            </div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Featured</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($projects as $p): ?>
                                        <tr>
                                            <td>#<?= $p['id'] ?></td>
                                            <td><?= htmlspecialchars($p['title']) ?></td>
                                            <td><?= htmlspecialchars($p['category']) ?></td>
                                            <td><?= $p['featured'] ? '<i class="fas fa-star" style="color:#f59e0b;"></i>' : '-' ?></td>
                                            <td><?= $p['completed_date'] ? formatDate($p['completed_date']) : '-' ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/admin/projects.php?edit=<?= $p['id'] ?>" class="btn btn-link btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="<?= BASE_URL ?>/admin/projects.php?toggle_featured=<?= $p['id'] ?>" class="btn btn-link btn-sm"><i class="fas fa-star"></i></a>
                                                <a href="<?= BASE_URL ?>/admin/projects.php?delete=<?= $p['id'] ?>" class="btn btn-link btn-sm" onclick="return confirm('Delete this project?')"><i class="fas fa-trash"></i></a>
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
