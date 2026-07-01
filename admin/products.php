<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

// Create product_images table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB");

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $id = (int)($_POST['product_id'] ?? 0);
    $name = sanitize($_POST['name']);
    $slug = sanitize($_POST['slug']);
    $category = sanitize($_POST['category']);
    $size = sanitize($_POST['size']);
    $description = $_POST['description'] ?? '';
    $features = $_POST['features'] ?? '';
    $specs = $_POST['specs'] ?? '';
    $price_label = sanitize($_POST['price_label']);
    $status = sanitize($_POST['status']);

    // Handle main image upload
    $mainImage = null;
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
        $mainImage = uploadFile($_FILES['main_image']);
    }

    if ($id > 0) {
        if ($mainImage) {
            $stmt = $pdo->prepare("UPDATE products SET name=?, slug=?, category=?, size=?, description=?, features=?, specs=?, price_label=?, status=?, image=? WHERE id=?");
            $stmt->execute([$name, $slug, $category, $size, $description, $features, $specs, $price_label, $status, $mainImage, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name=?, slug=?, category=?, size=?, description=?, features=?, specs=?, price_label=?, status=? WHERE id=?");
            $stmt->execute([$name, $slug, $category, $size, $description, $features, $specs, $price_label, $status, $id]);
        }
        $productId = $id;
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, slug, category, size, description, features, specs, price_label, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $category, $size, $description, $features, $specs, $price_label, $status, $mainImage]);
        $productId = $pdo->lastInsertId();
    }

    header('Location: ' . BASE_URL . '/admin/products.php?edit=' . $productId . '&saved=1');
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_images']) && isset($_GET['upload'])) {
    $productId = (int)$_GET['upload'];
    $sort = (int)($_POST['sort_order'] ?? 0);
    $caption = sanitize($_POST['caption'] ?? '');

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
            $filename = 'product_' . $productId . '_' . uniqid() . '.' . $ext;
            $dest = __DIR__ . '/../uploads/' . $filename;
            $dest2 = 'C:/xampp/htdocs/kimnest/uploads/' . $filename;

            if (move_uploaded_file($tmp, $dest)) {
                copy($dest, $dest2);
                $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image, caption, sort_order) VALUES (?, ?, ?, ?)");
                $stmt->execute([$productId, 'uploads/' . $filename, $caption, $sort + $i]);
            }
        }
    }
    header('Location: ' . BASE_URL . '/admin/products.php?edit=' . $productId . '&uploaded=1');
    exit;
}

// Handle delete image
if (isset($_GET['delete_img'])) {
    $imgId = (int)$_GET['delete_img'];
    $img = $pdo->prepare("SELECT image, product_id FROM product_images WHERE id=?");
    $img->execute([$imgId]);
    $imgData = $img->fetch();
    if ($imgData) {
        $filePath = __DIR__ . '/../' . $imgData['image'];
        if (file_exists($filePath)) unlink($filePath);
        $pdo->prepare("DELETE FROM product_images WHERE id=?")->execute([$imgId]);
        header('Location: ' . BASE_URL . '/admin/products.php?edit=' . $imgData['product_id'] . '&deleted=1');
        exit;
    }
}

// Handle delete product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
    header('Location: ' . BASE_URL . '/admin/products.php?deleted=1');
    exit;
}

$products = $pdo->query("SELECT * FROM products ORDER BY sort_order ASC, id ASC")->fetchAll();
$editProduct = null;
$editImages = [];
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $editProduct = $stmt->fetch();
    if ($editProduct) {
        $stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id=? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$editProduct['id']]);
        $editImages = $stmt->fetchAll();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | <?= SITE_NAME ?> Admin</title>
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
                <a href="<?= BASE_URL ?>/admin/products.php" class="active"><i class="fas fa-box"></i> Products</a>
                <a href="<?= BASE_URL ?>/admin/projects.php"><i class="fas fa-images"></i> Projects</a>
                <a href="<?= BASE_URL ?>/admin/newsletter.php"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Manage Products</h1>
                <a href="<?= BASE_URL ?>/admin/products.php" class="btn btn-outline <?= !isset($_GET['edit']) ? 'btn-primary' : '' ?>">All Products</a>
                <a href="<?= BASE_URL ?>/admin/products.php?edit=0" class="btn btn-primary">Add New Product</a>
            </div>

            <?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Product saved successfully.</div><?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">Product deleted.</div><?php endif; ?>
            <?php if (isset($_GET['uploaded'])): ?><div class="alert alert-success">Images uploaded.</div><?php endif; ?>

            <?php if (isset($editProduct) || isset($_GET['edit'])): ?>
                <form method="POST" enctype="multipart/form-data" class="card" style="margin-bottom: var(--space-xl);">
                    <div class="card-body">
                        <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?? 0 ?>">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" value="<?= $editProduct ? htmlspecialchars($editProduct['name']) : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text" name="slug" value="<?= $editProduct ? htmlspecialchars($editProduct['slug']) : '' ?>" placeholder="e.g. 20ft-standard" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category">
                                    <option value="Standard" <?= $editProduct && $editProduct['category'] === 'Standard' ? 'selected' : '' ?>>Standard</option>
                                    <option value="High Cube" <?= $editProduct && $editProduct['category'] === 'High Cube' ? 'selected' : '' ?>>High Cube</option>
                                    <option value="Specialty" <?= $editProduct && $editProduct['category'] === 'Specialty' ? 'selected' : '' ?>>Specialty</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Size</label>
                                <input type="text" name="size" value="<?= $editProduct ? htmlspecialchars($editProduct['size']) : '' ?>" placeholder="e.g. 20ft">
                            </div>
                            <div class="form-group">
                                <label>Price Label</label>
                                <input type="text" name="price_label" value="<?= $editProduct ? htmlspecialchars($editProduct['price_label']) : '' ?>" placeholder="e.g. Request Pricing">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status">
                                    <option value="available" <?= $editProduct && $editProduct['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                                    <option value="limited" <?= $editProduct && $editProduct['status'] === 'limited' ? 'selected' : '' ?>>Limited</option>
                                    <option value="unavailable" <?= $editProduct && $editProduct['status'] === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Main Image (Card Image)</label>
                            <input type="file" name="main_image" accept="image/*">
                            <div class="hint">Appears on product listing cards and detail page. <strong>Recommended: 800×600px</strong></div>
                            <?php if ($editProduct && !empty($editProduct['image'])): ?>
                                <div style="margin-top:8px;">
                                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($editProduct['image']) ?>" style="width:120px;height:90px;object-fit:cover;border-radius:6px;border:1px solid var(--admin-border);">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="4" style="width:100%;padding:10px;border:1px solid var(--admin-border);border-radius:4px;"><?= $editProduct ? htmlspecialchars($editProduct['description']) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Features (one per line)</label>
                            <textarea name="features" rows="6" style="width:100%;padding:10px;border:1px solid var(--admin-border);border-radius:4px;"><?= $editProduct ? htmlspecialchars($editProduct['features']) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Specifications (JSON)</label>
                            <textarea name="specs" rows="4" style="width:100%;padding:10px;border:1px solid var(--admin-border);border-radius:4px;font-family:monospace;font-size:13px;"><?= $editProduct ? htmlspecialchars($editProduct['specs']) : '' ?></textarea>
                        </div>
                        <button type="submit" name="save_product" class="btn btn-primary"><i class="fas fa-save"></i> Save Product</button>
                    </div>
                </form>

                <?php if ($editProduct && $editProduct['id'] > 0): ?>
                    <div class="card" style="margin-bottom: var(--space-xl);">
                        <div class="card-body">
                            <h3 style="margin-bottom:16px;">Product Images (Gallery)</h3>

                            <?php if (!empty($editImages)): ?>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-bottom:20px;">
                                    <?php foreach ($editImages as $img): ?>
                                        <div style="border:1px solid var(--admin-border);border-radius:6px;overflow:hidden;position:relative;">
                                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img['image']) ?>" style="width:100%;height:100px;object-fit:cover;display:block;">
                                            <div style="padding:6px 8px;font-size:11px;color:var(--admin-text-muted);">
                                                <?= htmlspecialchars($img['caption'] ?: 'No caption') ?>
                                            </div>
                                            <a href="<?= BASE_URL ?>/admin/products.php?delete_img=<?= $img['id'] ?>" onclick="return confirm('Delete this image?')" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.6);color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:12px;">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p style="color:var(--admin-text-muted);margin-bottom:16px;">No images yet. Upload below.</p>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>/admin/products.php?upload=<?= $editProduct['id'] ?>">
                                <div style="display:flex;gap:12px;align-items:end;flex-wrap:wrap;">
                                    <div>
                                        <label style="display:block;font-size:12px;color:var(--admin-text-muted);margin-bottom:4px;">Upload Images</label>
                                        <input type="file" name="images[]" multiple accept="image/*" required>
                                        <div class="hint" style="font-size:11px;margin-top:4px;"><strong>Recommended: 800×600px</strong></div>
                                    </div>
                                    <div>
                                        <label style="display:block;font-size:12px;color:var(--admin-text-muted);margin-bottom:4px;">Caption</label>
                                        <input type="text" name="caption" placeholder="Optional caption" style="padding:6px 10px;border:1px solid var(--admin-border);border-radius:4px;">
                                    </div>
                                    <button type="submit" name="upload_images" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="card">
                    <div class="card-body" style="padding:0;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Images</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                                        <td><?= $p['category'] ?></td>
                                        <td><?= $p['size'] ?></td>
                                        <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                                        <td>
                                            <?php
                                            $imgCount = $pdo->prepare("SELECT COUNT(*) FROM product_images WHERE product_id=?");
                                            $imgCount->execute([$p['id']]);
                                            echo $imgCount->fetchColumn() . ' image(s)';
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/products.php?edit=<?= $p['id'] ?>" class="btn btn-outline btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="<?= BASE_URL ?>/admin/products.php?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this product and all its images?')" class="btn btn-outline btn-sm" style="color:var(--admin-danger);border-color:var(--admin-danger);"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($products)): ?>
                                    <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--admin-text-muted);">No products yet. <a href="<?= BASE_URL ?>/admin/products.php?edit=0">Add your first product.</a></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
