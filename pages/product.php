<?php
require_once __DIR__ . '/../includes/db.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$product = $slug ? getProductBySlug($slug) : null;

if (!$product) {
    header('Location: ' . BASE_URL . '/products');
    exit;
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    $pid = (int)$product['id'];
    $qty = max(1, (int)($_POST['quantity'] ?? 1));

    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$pid] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'size' => $product['size'],
            'category' => $product['category'],
            'price_label' => $product['price_label'] ?? '',
            'qty' => $qty,
        ];
    }

    header('Location: ' . BASE_URL . '/product?slug=' . $product['slug'] . '&added=1');
    exit;
}

$pageTitle = $product['name'];
include __DIR__ . '/../includes/header.php';

$specs = json_decode($product['specs'], true) ?? [];
$features = array_filter(array_map('trim', explode("\n", $product['features'] ?? '')));
$galleryImages = getProductImages($product['id']);
$cartCount = !empty($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;

$heroBg = getContent('products', 'hero', 'bg_image', '');
$heroOverlay = getContent('products', 'hero', 'overlay_color', '#1a2e4a');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span>
            <a href="<?= BASE_URL ?>/products">Products</a> <span>/</span>
            <span><?= htmlspecialchars($product['name']) ?></span>
        </div>
    </div>
</section>

<?php if (isset($_GET['added'])): ?>
    <div class="container" style="margin-top: 20px;">
        <div class="alert alert-success" style="display:flex;align-items:center;justify-content:space-between;">
            <span><i class="fas fa-check-circle"></i> <?= htmlspecialchars($product['name']) ?> added to your quotation cart.</span>
            <a href="<?= BASE_URL ?>/cart" class="btn btn-primary btn-sm">View Cart (<?= $cartCount ?>)</a>
        </div>
    </div>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="product-detail">
            <div class="product-detail__main fade-left">
                <div class="product-detail__gallery">
                    <?php
                    $allImages = [];
                    if (!empty($product['image'])) {
                        $allImages[] = ['image' => $product['image'], 'caption' => ''];
                    }
                    foreach ($galleryImages as $gi) {
                        $allImages[] = $gi;
                    }
                    ?>
                    <?php if (!empty($allImages)): ?>
                        <div class="product-detail__hero-img">
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($allImages[0]['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="productHeroImg">
                        </div>
                        <?php if (count($allImages) > 1): ?>
                            <div class="product-detail__thumbs">
                                <?php foreach ($allImages as $img): ?>
                                    <div class="product-detail__thumb" onclick="document.getElementById('productHeroImg').src='<?= BASE_URL ?>/<?= htmlspecialchars($img['image']) ?>'">
                                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img['image']) ?>" alt="<?= htmlspecialchars($img['caption'] ?? '') ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="product-detail__placeholder">
                            <i class="fas fa-box"></i>
                            <span><?= htmlspecialchars($product['size']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-detail__description fade-in">
                    <h3>About This Product</h3>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>

                <?php if (!empty($features)): ?>
                    <div class="product-detail__features fade-in">
                        <h3>Key Features</h3>
                        <ul class="product-detail__feature-list">
                            <?php foreach ($features as $f): ?>
                                <li><i class="fas fa-check"></i> <?= htmlspecialchars($f) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="product-detail__sidebar fade-right">
                <div class="product-detail__purchase-card">
                    <div class="product-detail__purchase-header">
                        <span class="product-list-card__category"><?= htmlspecialchars($product['category']) ?> — <?= htmlspecialchars($product['size']) ?></span>
                        <?php if ($product['status'] === 'limited'): ?>
                            <span class="product-list-card__badge product-list-card__badge--limited">Limited Stock</span>
                        <?php elseif ($product['status'] === 'unavailable'): ?>
                            <span class="product-list-card__badge product-list-card__badge--unavailable">Sold Out</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-detail__price"><?= htmlspecialchars($product['price_label'] ?: 'Request Pricing') ?></div>

                    <?php if ($product['status'] !== 'unavailable'): ?>
                        <form method="POST" class="product-detail__form">
                            <label class="product-detail__qty-label">Quantity</label>
                            <div class="product-detail__qty-row">
                                <button type="button" class="product-detail__qty-btn" onclick="var inp=document.getElementById('qty'); inp.value=Math.max(1,parseInt(inp.value)-1); updateQuoteLink()">−</button>
                                <input type="number" id="qty" name="quantity" value="1" min="1" max="99" class="product-detail__qty-input" onchange="updateQuoteLink()">
                                <button type="button" class="product-detail__qty-btn" onclick="var inp=document.getElementById('qty'); inp.value=Math.min(99,parseInt(inp.value)+1); updateQuoteLink()">+</button>
                            </div>
                            <button type="submit" name="add_to_cart" value="1" class="btn btn-primary btn-block product-detail__add-btn">
                                <i class="fas fa-cart-plus"></i> Add to Quotation Cart
                            </button>
                        </form>

                        <a href="<?= BASE_URL ?>/request-quote?product=<?= urlencode($product['name']) ?>&size=<?= urlencode($product['size']) ?>&qty=1" class="btn btn-outline btn-block" id="requestQuoteLink">
                            <i class="fas fa-paper-plane"></i> Request a Quote
                        </a>
                        <script>function updateQuoteLink(){var qty=document.getElementById('qty').value||1;var link=document.getElementById('requestQuoteLink');link.href='<?= BASE_URL ?>/request-quote?product=<?= urlencode($product['name']) ?>&size=<?= urlencode($product['size']) ?>&qty='+qty;}</script>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/contact" class="btn btn-primary btn-block">Contact for Availability</a>
                    <?php endif; ?>

                    <?php if ($cartCount > 0): ?>
                        <a href="<?= BASE_URL ?>/cart" class="btn btn-outline btn-block cart-summary-btn">
                            <i class="fas fa-shopping-cart"></i> View Cart (<?= $cartCount ?> items)
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($specs)): ?>
                    <div class="product-detail__specs-card">
                        <h3>Specifications</h3>
                        <table class="specs-table">
                            <?php foreach ($specs as $label => $value): ?>
                                <tr>
                                    <td class="specs-table__label"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $label))) ?></td>
                                    <td class="specs-table__value"><?= htmlspecialchars($value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="product-detail__help-card">
                    <h3>Need Help?</h3>
                    <p>Our team is ready to assist you with sizing, customization, and delivery options.</p>
                    <a href="https://wa.me/<?= getSetting('site_whatsapp', SITE_WHATSAPP) ?>" class="btn btn-whatsapp btn-block btn-sm" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp Us
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
