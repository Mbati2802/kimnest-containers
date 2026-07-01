<?php
$pageTitle = 'Products';
include __DIR__ . '/../includes/header.php';

$categories = getProductCategories();
$cat = isset($_GET['cat']) ? sanitize($_GET['cat']) : null;
$products = getProducts($cat);

$heroBg = getContent('products', 'hero', 'bg_image', '');
$heroOverlay = getContent('products', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('products', 'hero', 'description', '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1>Our Products</h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>Products</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Container Products</div>
            <h2>Premium Container Solutions for Every Need</h2>
            <p>We supply and fabricate a wide range of container products designed to meet the evolving needs of individuals, businesses, and industries.</p>
        </div>

        <div class="products-filter fade-in">
            <a href="<?= BASE_URL ?>/products" class="btn <?= !$cat ? 'btn-primary' : 'btn-outline-primary' ?>">All</a>
            <?php foreach ($categories as $c): ?>
                <a href="<?= BASE_URL ?>/products?cat=<?= urlencode($c) ?>" class="btn <?= $cat === $c ? 'btn-primary' : 'btn-outline-primary' ?>"><?= $c ?></a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="projects-empty fade-in">
                <i class="fas fa-box-open"></i>
                <h3>No Products Found</h3>
                <p>Check back soon for our latest container products!</p>
            </div>
        <?php else: ?>
            <div class="products-grid stagger">
                <?php foreach ($products as $product): ?>
                    <a href="<?= BASE_URL ?>/product?slug=<?= htmlspecialchars($product['slug']) ?>" class="product-list-card fade-in">
                        <div class="product-list-card__image">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php else: ?>
                                <div class="product-list-card__placeholder">
                                    <i class="fas fa-box"></i>
                                    <span><?= htmlspecialchars($product['size']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($product['status'] === 'limited'): ?>
                                <span class="product-list-card__badge product-list-card__badge--limited">Limited</span>
                            <?php elseif ($product['status'] === 'unavailable'): ?>
                                <span class="product-list-card__badge product-list-card__badge--unavailable">Sold Out</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-list-card__body">
                            <div class="product-list-card__category"><?= htmlspecialchars($product['category']) ?> — <?= htmlspecialchars($product['size']) ?></div>
                            <h3 class="product-list-card__title"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-list-card__desc"><?= htmlspecialchars(truncate($product['description'], 120)) ?></p>
                            <div class="product-list-card__footer">
                                <span class="product-list-card__price"><?= htmlspecialchars($product['price_label'] ?: 'Request Pricing') ?></span>
                                <span class="product-list-card__cta">View Details <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="cta-content zoom-in">
            <h2>Can't Find What You're Looking For?</h2>
            <p>We specialize in custom container solutions. Tell us about your project and we'll create something perfect for you.</p>
            <div class="cta-btns">
                <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary btn-lg">Get a Custom Quote</a>
                <a href="https://wa.me/<?= getSetting('site_whatsapp', SITE_WHATSAPP) ?>" class="btn btn-whatsapp btn-lg" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
