<?php
$pageTitle = 'Blog';
include __DIR__ . '/../includes/header.php';

$posts = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (Exception $e) {}

$categories = array_unique(array_filter(array_column($posts, 'category')));
$selectedCat = isset($_GET['cat']) ? sanitize($_GET['cat']) : '';

$heroBg = getContent('blog', 'hero', 'bg_image', '');
$heroOverlay = getContent('blog', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('blog', 'hero', 'description', '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1><?= htmlspecialchars(getContent('blog', 'hero', 'overline', 'Blog')) ?></h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>Blog</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="blog-top fade-in">
            <div>
                <h2 class="blog-top__title">All Articles</h2>
                <p class="blog-top__desc">Find helpful articles and expert advice on container solutions, fabrication, design, and more.</p>
            </div>
        </div>

        <div class="blog-toolbar fade-in">
            <div class="blog-search">
                <i class="fas fa-search"></i>
                <input type="text" id="blogSearch" placeholder="Search articles..." oninput="filterBlogPosts()">
            </div>
            <div class="blog-tabs" id="blogTabs">
                <button class="blog-tab active" data-cat="" onclick="filterByCategory(this)">All Articles</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="blog-tab" data-cat="<?= htmlspecialchars($cat) ?>" onclick="filterByCategory(this)"><?= htmlspecialchars($cat) ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($posts)): ?>
            <div class="blog-empty fade-in">
                <i class="fas fa-newspaper"></i>
                <h3>Coming Soon</h3>
                <p>We're working on great content for you. Check back soon!</p>
            </div>
        <?php else: ?>
            <div class="blog-grid" id="blogGrid">
                <?php foreach ($posts as $post): ?>
                    <a href="<?= BASE_URL ?>/blog/<?= $post['slug'] ?>" class="blog-card-new fade-in" data-category="<?= htmlspecialchars($post['category'] ?? '') ?>" data-title="<?= htmlspecialchars(strtolower($post['title'])) ?>">
                        <div class="blog-card-new__image">
                            <?php if ($post['image']): ?>
                                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                            <?php else: ?>
                                <div class="blog-card-new__placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="blog-card-new__body">
                            <div class="blog-card-new__meta">
                                <?php if ($post['category']): ?>
                                    <span class="blog-card-new__tag"><?= htmlspecialchars($post['category']) ?></span>
                                <?php endif; ?>
                                <span class="blog-card-new__date"><?= formatDate($post['created_at']) ?></span>
                            </div>
                            <h3 class="blog-card-new__title"><?= htmlspecialchars($post['title']) ?></h3>
                            <p class="blog-card-new__excerpt"><?= htmlspecialchars(truncate($post['excerpt'] ?? $post['content'], 140)) ?></p>
                            <span class="blog-card-new__link">Learn More <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function filterByCategory(btn) {
    document.querySelectorAll('.blog-tab').forEach(function(t) { t.classList.remove('active'); });
    btn.classList.add('active');
    filterBlogPosts();
}

function filterBlogPosts() {
    var cat = document.querySelector('.blog-tab.active').getAttribute('data-cat');
    var search = document.getElementById('blogSearch').value.toLowerCase();
    document.querySelectorAll('.blog-card-new').forEach(function(card) {
        var cardCat = card.getAttribute('data-category');
        var cardTitle = card.getAttribute('data-title');
        var matchCat = !cat || cardCat === cat;
        var matchSearch = !search || cardTitle.indexOf(search) > -1;
        card.style.display = matchCat && matchSearch ? '' : 'none';
    });
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
