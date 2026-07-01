<?php
require_once __DIR__ . '/../includes/functions.php';

$slug = $_GET['slug'] ?? '';
$post = getPostBySlug($slug);

if (!$post) {
    header('Location: ' . BASE_URL . '/blog');
    exit;
}

// Increment views
$stmt = $pdo->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
$stmt->execute([$post['id']]);

$pageTitle = $post['title'];
include __DIR__ . '/../includes/header.php';

$heroBg = getContent('blog', 'hero', 'bg_image', '');
$heroOverlay = getContent('blog', 'hero', 'overlay_color', '#1a2e4a');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <a href="<?= BASE_URL ?>/blog">Blog</a> <span>/</span> <span><?= truncate($post['title'], 30) ?></span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width: 800px;">
        <div class="blog-meta" style="margin-bottom: var(--space-lg);">
            <span><i class="fas fa-calendar"></i> <?= formatDate($post['created_at']) ?></span>
            <?php if ($post['category']): ?>
                <span><i class="fas fa-folder"></i> <?= $post['category'] ?></span>
            <?php endif; ?>
            <span><i class="fas fa-eye"></i> <?= $post['views'] ?> views</span>
        </div>
        
        <?php if ($post['image']): ?>
            <img src="<?= BASE_URL ?>/<?= $post['image'] ?>" alt="<?= $post['title'] ?>" style="width: 100%; border-radius: var(--radius-md); margin-bottom: var(--space-xl);">
        <?php endif; ?>
        
        <article style="line-height: 1.8; color: var(--dark);">
            <?= nl2br($post['content']) ?>
        </article>
        
        <div style="border-top: 1px solid var(--gray-200); margin-top: var(--space-2xl); padding-top: var(--space-lg);">
            <a href="<?= BASE_URL ?>/blog" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Back to Blog</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
