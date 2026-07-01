<?php
require_once __DIR__ . '/../includes/db.php';

// Handle remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $removeId = (int)$_POST['remove_item'];
    if (isset($_SESSION['cart'][$removeId])) {
        unset($_SESSION['cart'][$removeId]);
    }
    header('Location: ' . BASE_URL . '/cart');
    exit;
}

// Handle update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
    $updateId = (int)$_POST['update_qty'];
    $newQty = max(1, (int)($_POST['quantity'] ?? 1));
    if (isset($_SESSION['cart'][$updateId])) {
        $_SESSION['cart'][$updateId]['qty'] = $newQty;
    }
    header('Location: ' . BASE_URL . '/cart');
    exit;
}

$pageTitle = 'Quotation Cart';
include __DIR__ . '/../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$cartCount = !empty($cart) ? array_sum(array_column($cart, 'qty')) : 0;

// Fetch product images for cart items
$productImages = [];
if (!empty($cart)) {
    $ids = implode(',', array_keys($cart));
    $res = $pdo->query("SELECT id, image FROM products WHERE id IN ($ids)");
    while ($row = $res->fetch()) {
        $productImages[$row['id']] = $row['image'];
    }
}
$categoryCounts = [];
foreach ($cart as $item) {
    $cat = $item['category'] ?? 'Other';
    $categoryCounts[$cat] = ($categoryCounts[$cat] ?? 0) + $item['qty'];
}

$heroBg = getContent('cart', 'hero', 'bg_image', '');
$heroOverlay = getContent('cart', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('cart', 'hero', 'description', '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1><?= htmlspecialchars(getContent('cart', 'hero', 'overline', 'Quotation Cart')) ?></h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>Cart</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($cart)): ?>
            <div class="projects-empty fade-in">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your Cart is Empty</h3>
                <p>Browse our products and add items to request a quotation.</p>
                <a href="<?= BASE_URL ?>/products" class="btn btn-primary" style="margin-top: 16px;">View Products</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items fade-left">
                    <div class="cart-header-row">
                        <span class="cart-col-product">Product</span>
                        <span class="cart-col-qty">Qty</span>
                        <span class="cart-col-action">Action</span>
                    </div>

                    <?php foreach ($cart as $item): ?>
                        <?php $img = $productImages[$item['id']] ?? null; ?>
                        <div class="cart-item">
                            <div class="cart-col-product">
                                <div class="cart-item__info">
                                    <?php if ($img): ?>
                                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item__thumb">
                                    <?php endif; ?>
                                    <div>
                                        <h4><?= htmlspecialchars($item['name']) ?></h4>
                                        <span class="cart-item__meta"><?= htmlspecialchars($item['category']) ?> — <?= htmlspecialchars($item['size']) ?></span>
                                        <?php if (!empty($item['price_label'])): ?>
                                            <span class="cart-item__price"><?= htmlspecialchars($item['price_label']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-col-qty">
                                <form method="POST" style="display:flex;align-items:center;gap:4px;">
                                    <button type="button" class="product-detail__qty-btn" onclick="var inp=this.nextElementSibling; inp.value=Math.max(1,parseInt(inp.value)-1); this.closest('form').submit()">−</button>
                                    <input type="number" name="quantity" value="<?= $item['qty'] ?>" min="1" max="99" class="product-detail__qty-input product-detail__qty-input--sm" onchange="this.closest('form').submit()">
                                    <button type="button" class="product-detail__qty-btn" onclick="var inp=this.previousElementSibling; inp.value=Math.min(99,parseInt(inp.value)+1); this.closest('form').submit()">+</button>
                                    <input type="hidden" name="update_qty" value="<?= $item['id'] ?>">
                                </form>
                            </div>
                            <div class="cart-col-action">
                                <form method="POST">
                                    <button type="submit" name="remove_item" value="<?= $item['id'] ?>" class="btn btn-outline btn-sm" style="color:var(--danger, #ef4444);border-color:var(--danger, #ef4444);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary fade-right">
                    <h3>Summary</h3>
                    <div class="cart-summary__row">
                        <span>Total Items</span>
                        <strong><?= $cartCount ?></strong>
                    </div>
                    <?php if (count($categoryCounts) > 1): ?>
                        <div class="cart-summary__categories">
                            <span class="cart-summary__cat-label">By Category</span>
                            <?php foreach ($categoryCounts as $cat => $count): ?>
                                <div class="cart-summary__cat-row">
                                    <span><?= htmlspecialchars($cat) ?></span>
                                    <span><?= $count ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="cart-summary__note">
                        <i class="fas fa-info-circle"></i>
                        Pricing is provided upon request. Submit your cart as a quotation request and our team will get back to you with a detailed quote.
                    </div>
                    <a href="<?= BASE_URL ?>/request-quote?from_cart=1" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Submit for Quotation
                    </a>
                    <a href="<?= BASE_URL ?>/products" class="btn btn-outline btn-block">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
