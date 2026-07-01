<?php
$pageTitle = 'Request a Quote';
include __DIR__ . '/../includes/header.php';
$flash = getFlash();

$fromCart = isset($_GET['from_cart']) || isset($_GET['thank_you']);
$cart = $_SESSION['cart'] ?? [];
$cartItems = !empty($cart) ? $cart : [];
$cartTotal = !empty($cartItems) ? array_sum(array_column($cartItems, 'qty')) : 0;

$prefillSize = isset($_GET['size']) ? sanitize($_GET['size']) : '';
$prefillQty = isset($_GET['qty']) ? max(1, (int)$_GET['qty']) : 1;

$heroBg = getContent('quote', 'hero', 'bg_image', '');
$heroOverlay = getContent('quote', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('quote', 'hero', 'description', '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1><?= htmlspecialchars(getContent('quote', 'hero', 'overline', 'Request a Quote')) ?></h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>Request a Quote</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (isset($_GET['thank_you'])): ?>
            <div class="contact-grid" style="justify-content:center;">
                <div style="text-align:center;padding:var(--space-3xl) 0;max-width:500px;margin:0 auto;">
                    <div style="font-size:64px;color:var(--primary);margin-bottom:var(--space-lg);"><i class="fas fa-check-circle"></i></div>
                    <h2 style="margin-bottom:var(--space-md);">Thank You!</h2>
                    <p style="color:var(--gray-500);margin-bottom:var(--space-xl);line-height:1.7;">
                        Your quotation request has been received successfully. Our team will review your cart items and contact you within 24 hours with a detailed quote.
                    </p>
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary"><i class="fas fa-box"></i> Browse Products</a>
                    <a href="<?= BASE_URL ?>/" class="btn btn-outline" style="margin-left:8px;">Back to Home</a>
                </div>
            </div>
        <?php else: ?>
        <div class="quote-layout">
            <div class="quote-form-col">
                <div class="quote-form-card">
                    <h3 style="margin-bottom: var(--space-md); font-size: 16px;">Get a Free Quote</h3>
                    
                    <?php if ($flash): ?>
                        <div class="alert alert-<?= $flash['type'] ?>" style="margin-bottom:12px;padding:8px 12px;font-size:13px;"><?= $flash['message'] ?></div>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>/api/quote_handler.php" method="POST" enctype="multipart/form-data" data-ajax class="form--compact">
                        <?php if (!empty($cartItems)): ?>
                            <input type="hidden" name="cart_data" value="<?= htmlspecialchars(json_encode($cartItems)) ?>">
                        <?php endif; ?>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" name="full_name" required>
                            </div>
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" name="company_name" placeholder="Optional">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone *</label>
                                <input type="tel" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Project Type</label>
                                <select name="project_type">
                                    <option value="">Select</option>
                                    <option value="Container Home">Container Home</option>
                                    <option value="Office Container">Office Container</option>
                                    <option value="Retail Shop">Retail Shop</option>
                                    <option value="Restaurant/Cafe">Restaurant/Cafe</option>
                                    <option value="Storage Unit">Storage Unit</option>
                                    <option value="Classroom">Classroom</option>
                                    <option value="Clinic">Clinic</option>
                                    <option value="Security Cabin">Security Cabin</option>
                                    <option value="Custom Fabrication">Custom Fabrication</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Size</label>
                                <select name="container_size">
                                    <option value="">Select</option>
                                    <option value="10ft" <?= $prefillSize === '10ft' ? 'selected' : '' ?>>10ft</option>
                                    <option value="20ft" <?= $prefillSize === '20ft' ? 'selected' : '' ?>>20ft</option>
                                    <option value="40ft" <?= $prefillSize === '40ft' ? 'selected' : '' ?>>40ft</option>
                                    <option value="High Cube" <?= $prefillSize === 'High Cube' ? 'selected' : '' ?>>High Cube</option>
                                    <option value="Not Sure" <?= $prefillSize === 'Not Sure' ? 'selected' : '' ?>>Not Sure</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" value="<?= !empty($cartItems) ? 1 : $prefillQty ?>" min="1" <?= !empty($cartItems) ? 'disabled' : '' ?>>
                                <?php if (!empty($cartItems)): ?><input type="hidden" name="quantity" value="1"><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="project_location" placeholder="e.g., Nairobi">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Intended Use</label>
                            <input type="text" name="intended_use" placeholder="Brief description of how you plan to use it">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Estimated Budget</label>
                                <select name="budget">
                                    <option value="">Select Range</option>
                                    <option value="Under 100,000">Under KES 100,000</option>
                                    <option value="100,000 - 250,000">KES 100,000 - 250,000</option>
                                    <option value="250,000 - 500,000">KES 250,000 - 500,000</option>
                                    <option value="500,000 - 1,000,000">KES 500,000 - 1,000,000</option>
                                    <option value="1,000,000+">KES 1,000,000+</option>
                                    <option value="Not Sure">Not Sure</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Timeline</label>
                                <input type="text" name="completion_date" placeholder="e.g., Within 2 months">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Project Description</label>
                            <textarea name="description" placeholder="Tell us more about your project..." rows="3"><?php
                                if (isset($_GET['product'])) {
                                    echo htmlspecialchars('I am interested in: ' . $_GET['product']);
                                } elseif (!empty($cartItems)) {
                                    $desc = "I would like a quotation for the following items from my cart:\n";
                                    foreach ($cartItems as $item) {
                                        $desc .= "- " . $item['name'] . " (" . $item['size'] . ") x " . $item['qty'] . "\n";
                                    }
                                    echo htmlspecialchars($desc);
                                }
                            ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Attachments</label>
                            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" style="padding:6px 10px;">
                            <small style="color: var(--gray-400); font-size: 11px;">Max 10MB. JPG, PNG, PDF, DOC.</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Preferred Contact</label>
                            <select name="contact_method">
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px 16px; font-size: 14px;">Submit Quote Request</button>
                    </form>
                </div>
            </div>

            <div class="quote-info-col">
                <div class="overline">Get Started</div>
                <h2>Let's Bring Your Vision to Life</h2>
                <p style="color: var(--gray-500); margin-bottom: var(--space-lg);">Whether you're planning a container home, office, retail shop, restaurant, storage facility, or a fully customized modular structure, Kimnest Containers is ready to turn your ideas into reality.</p>

                <?php if (!empty($cartItems)): ?>
                    <div style="background:var(--light);border:1px solid var(--gray-100);border-radius:var(--radius-md);padding:var(--space-lg);margin-bottom:var(--space-xl);">
                        <h4 style="font-size:14px;margin-bottom:var(--space-md);"><i class="fas fa-shopping-cart" style="color:var(--primary);margin-right:6px;"></i> Your Cart Items (<?= $cartTotal ?> total)</h4>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <?php foreach ($cartItems as $item): ?>
                                <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:6px 0;border-bottom:1px solid var(--gray-100);">
                                    <div>
                                        <strong><?= htmlspecialchars($item['name']) ?></strong>
                                        <span style="color:var(--gray-400);margin-left:6px;"><?= htmlspecialchars($item['size']) ?> × <?= $item['qty'] ?></span>
                                    </div>
                                    <span style="color:var(--primary);font-weight:500;font-size:12px;"><?= htmlspecialchars($item['category']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <h4 style="margin: var(--space-xl) 0 var(--space-md);">Why Request a Quote?</h4>
                <ul class="service-list" style="margin-bottom: var(--space-xl);">
                    <li>A customized project proposal tailored to your needs</li>
                    <li>Professional recommendations from our fabrication specialists</li>
                    <li>Transparent pricing with no hidden costs</li>
                    <li>Estimated fabrication and delivery timelines</li>
                    <li>Design suggestions to maximize functionality and value</li>
                    <li>Expert guidance on available customization options</li>
                </ul>

                <h4 style="margin: var(--space-xl) 0 var(--space-md);">Our Process</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                    <div style="display: flex; gap: 15px; align-items: flex-start;">
                        <div class="step-number" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">1</div>
                        <div>
                            <h4 style="margin-bottom: var(--space-xs);">Submit Request</h4>
                            <p style="font-size: 13px; color: var(--gray-500);">Complete the form with your project details</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; align-items: flex-start;">
                        <div class="step-number" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">2</div>
                        <div>
                            <h4 style="margin-bottom: var(--space-xs);">Consultation</h4>
                            <p style="font-size: 13px; color: var(--gray-500);">We'll contact you to discuss requirements</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; align-items: flex-start;">
                        <div class="step-number" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">3</div>
                        <div>
                            <h4 style="margin-bottom: var(--space-xs);">Assessment</h4>
                            <p style="font-size: 13px; color: var(--gray-500);">Our team reviews and prepares a solution</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; align-items: flex-start;">
                        <div class="step-number" style="width: 40px; height: 40px; min-width: 40px; font-size: 16px;">4</div>
                        <div>
                            <h4 style="margin-bottom: var(--space-xs);">Receive Proposal</h4>
                            <p style="font-size: 13px; color: var(--gray-500);">Get detailed quotation with pricing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
