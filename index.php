<?php
$pageTitle = '';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-bg">
        <div class="hero-overlay"></div>
    </div>
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="overline"><?= htmlspecialchars(getContent('home', 'hero', 'overline', 'Kimnest Containers')) ?></div>
                <h1><?= htmlspecialchars(getContent('home', 'hero', 'title', 'Transforming Shipping Containers Into Exceptional Spaces')) ?></h1>
                <p><?= htmlspecialchars(getContent('home', 'hero', 'description', 'We specialize in supplying, designing, fabricating, and modifying premium shipping containers into innovative, durable, and affordable spaces tailored to your needs.')) ?></p>
                <div class="hero-btns">
                    <a href="<?= BASE_URL ?><?= htmlspecialchars(getContent('home', 'hero', 'btn1_link', '/request-quote')) ?>" class="btn btn-primary btn-lg"><?= htmlspecialchars(getContent('home', 'hero', 'btn1_text', 'Request a Free Quote')) ?></a>
                    <a href="<?= BASE_URL ?><?= htmlspecialchars(getContent('home', 'hero', 'btn2_link', '/projects')) ?>" class="btn btn-outline btn-lg"><?= htmlspecialchars(getContent('home', 'hero', 'btn2_text', 'View Our Projects')) ?></a>
                </div>
            </div>
            <div class="hero-image">
                <?php
                $heroImages = [];
                for ($i = 1; $i <= 3; $i++) {
                    $key = $i === 1 ? 'hero_image' : 'hero_image_' . $i;
                    $val = getContent('home', 'hero', $key, '');
                    if (!empty($val)) $heroImages[] = $val;
                }
                ?>
                <?php if (!empty($heroImages)): ?>
                    <div class="hero-carousel" id="heroCarousel">
                        <div class="hero-carousel-track" id="heroTrack">
                            <?php foreach ($heroImages as $img): ?>
                                <div class="hero-carousel-slide">
                                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img) ?>" alt="Kimnest Containers">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($heroImages) > 1): ?>
                            <button class="hero-carousel-btn prev" onclick="heroScroll(-1)"><i class="fas fa-chevron-left"></i></button>
                            <button class="hero-carousel-btn next" onclick="heroScroll(1)"><i class="fas fa-chevron-right"></i></button>
                            <div class="hero-carousel-dots" id="heroDots">
                                <?php foreach ($heroImages as $i => $img): ?>
                                    <button class="hero-carousel-dot <?= $i === 0 ? 'active' : '' ?>" onclick="heroGoTo(<?= $i ?>)"></button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="hero-image-placeholder"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Hanging Cards -->
<?php
$card1visible = !empty(getContent('home', 'stats', 'stat1_number', ''));
$card2visible = !empty(getContent('home', 'stats', 'stat2_number', ''));
$card3visible = !empty(getContent('home', 'stats', 'stat3_number', ''));
$hasCards = $card1visible || $card2visible || $card3visible;
?>
<?php if ($hasCards): ?>
<div class="hanging-cards">
    <div class="container">
        <?php if ($card1visible): ?>
        <div class="hanging-card" style="--fc-bg:<?= htmlspecialchars(getSetting('card1_bg', '#ffffff')) ?>;--fc-num:<?= htmlspecialchars(getSetting('card1_number_color', '#3e7ac5')) ?>;--fc-accent:<?= htmlspecialchars(getSetting('card1_accent_color', '#f5c140')) ?>;--fc-text:<?= htmlspecialchars(getSetting('card1_text_color', '#1a1d23')) ?>;--fc-sub:<?= htmlspecialchars(getSetting('card1_sub_color', '#9ba3af')) ?>;">
            <div class="hanging-card-icon">
                <div class="number"><?= htmlspecialchars(getContent('home', 'stats', 'stat1_number', '500+')) ?></div>
            </div>
            <div class="hanging-card-text">
                <h4><?= htmlspecialchars(getContent('home', 'stats', 'stat1_label', 'Projects Completed')) ?></h4>
                <p><?= htmlspecialchars(getContent('home', 'stats', 'stat1_sub', 'Delivered across Kenya')) ?></p>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($card2visible): ?>
        <div class="hanging-card" style="--fc-bg:<?= htmlspecialchars(getSetting('card2_bg', '#ffffff')) ?>;--fc-num:<?= htmlspecialchars(getSetting('card2_number_color', '#3e7ac5')) ?>;--fc-accent:<?= htmlspecialchars(getSetting('card2_accent_color', '#f5c140')) ?>;--fc-text:<?= htmlspecialchars(getSetting('card2_text_color', '#1a1d23')) ?>;--fc-sub:<?= htmlspecialchars(getSetting('card2_sub_color', '#9ba3af')) ?>;">
            <div class="hanging-card-icon">
                <div class="number"><?= htmlspecialchars(getContent('home', 'stats', 'stat2_number', '10+')) ?></div>
            </div>
            <div class="hanging-card-text">
                <h4><?= htmlspecialchars(getContent('home', 'stats', 'stat2_label', 'Years Experience')) ?></h4>
                <p><?= htmlspecialchars(getContent('home', 'stats', 'stat2_sub', 'Trusted by hundreds')) ?></p>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($card3visible): ?>
        <div class="hanging-card" style="--fc-bg:<?= htmlspecialchars(getSetting('card3_bg', '#ffffff')) ?>;--fc-num:<?= htmlspecialchars(getSetting('card3_number_color', '#3e7ac5')) ?>;--fc-accent:<?= htmlspecialchars(getSetting('card3_accent_color', '#f5c140')) ?>;--fc-text:<?= htmlspecialchars(getSetting('card3_text_color', '#1a1d23')) ?>;--fc-sub:<?= htmlspecialchars(getSetting('card3_sub_color', '#9ba3af')) ?>;">
            <div class="hanging-card-icon">
                <div class="number"><?= htmlspecialchars(getContent('home', 'stats', 'stat3_number', '100%')) ?></div>
            </div>
            <div class="hanging-card-text">
                <h4><?= htmlspecialchars(getContent('home', 'stats', 'stat3_label', 'Client Satisfaction')) ?></h4>
                <p><?= htmlspecialchars(getContent('home', 'stats', 'stat3_sub', 'Quality guaranteed')) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- About Editorial Section -->
<?php if (!empty(getContent('home', 'about', 'title', ''))): ?>
<section class="section">
    <div class="container">
        <div class="editorial fade-left">
            <div class="editorial-content">
                <div class="overline"><?= htmlspecialchars(getContent('home', 'about', 'overline', 'About Us')) ?></div>
                <h2><?= htmlspecialchars(getContent('home', 'about', 'title', 'We Build Spaces That Inspire')) ?></h2>
                <p><?= htmlspecialchars(getContent('home', 'about', 'description', 'Every great project begins with a vision. At Kimnest Containers, we believe that shipping containers offer limitless possibilities for modern construction, business innovation, and affordable living.')) ?></p>
                <p><?= htmlspecialchars(getContent('home', 'about', 'description2', 'From compact site offices and stylish retail shops to luxurious container homes and fully customized commercial spaces, we create solutions that combine durability, functionality, and aesthetic appeal.')) ?></p>
                <a href="<?= BASE_URL ?><?= htmlspecialchars(getContent('home', 'about', 'btn_link', '/about')) ?>" class="btn btn-primary"><?= htmlspecialchars(getContent('home', 'about', 'btn_text', 'Learn More About Us')) ?></a>
            </div>
            <div class="editorial-image">
                <?php $aboutImg = getContent('home', 'about', 'image', ''); ?>
                <?php if (!empty($aboutImg)): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($aboutImg) ?>" alt="About Kimnest" onerror="this.parentElement.classList.add('placeholder-img')">
                <?php else: ?>
                    <div class="placeholder-img"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Choose Us -->
<?php if (!empty(getContent('home', 'whyus', 'title', ''))): ?>
<section class="section bg-light">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline"><?= htmlspecialchars(getContent('home', 'whyus', 'overline', 'Why Choose Us')) ?></div>
            <h2><?= htmlspecialchars(getContent('home', 'whyus', 'title', 'Built on Trust, Delivered with Excellence')) ?></h2>
            <p><?= htmlspecialchars(getContent('home', 'whyus', 'description', 'We combine expertise, quality materials, and customer-first service to deliver container solutions that exceed expectations.')) ?></p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-lightbulb"></i></div>
                <h4>Innovative Design</h4>
                <p>We transform ordinary shipping containers into extraordinary spaces that are functional, modern, and built to last.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-medal"></i></div>
                <h4>Quality Craftsmanship</h4>
                <p>Every project is completed using premium materials, skilled workmanship, and strict quality control measures.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-cogs"></i></div>
                <h4>Custom Fabrication</h4>
                <p>No two clients are the same. Every solution is designed around your unique vision, budget, and requirements.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-truck"></i></div>
                <h4>Reliable Delivery</h4>
                <p>We deliver completed projects safely and efficiently across Kenya.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <h4>Affordable Solutions</h4>
                <p>Enjoy cost-effective alternatives to conventional construction without compromising quality.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h4>Customer-Centered</h4>
                <p>From consultation to installation, we prioritize clear communication and customer satisfaction.</p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services Editorial Section -->
<?php if (!empty(getContent('home', 'services', 'title', ''))): ?>
<section class="section">
    <div class="container">
        <div class="editorial editorial--reverse fade-right">
            <div class="editorial-content">
                <div class="overline"><?= htmlspecialchars(getContent('home', 'services', 'overline', 'Our Services')) ?></div>
                <h2><?= htmlspecialchars(getContent('home', 'services', 'title', 'Comprehensive Container Solutions')) ?></h2>
                <p><?= htmlspecialchars(getContent('home', 'services', 'description', 'From initial consultation and custom design to professional fabrication and nationwide delivery, we handle every aspect of your container project with precision and care.')) ?></p>
                <ul class="service-list">
                    <li>Container Sales — New and used containers in all sizes</li>
                    <li>Custom Fabrication — Offices, homes, shops, and more</li>
                    <li>Modifications — Doors, windows, insulation, and interiors</li>
                    <li>Delivery & Installation — Kenya-wide service</li>
                </ul>
                <a href="<?= BASE_URL ?><?= htmlspecialchars(getContent('home', 'services', 'btn_link', '/services')) ?>" class="btn btn-primary"><?= htmlspecialchars(getContent('home', 'services', 'btn_text', 'Explore Our Services')) ?></a>
            </div>
            <div class="editorial-image">
                <?php $servicesImg = getContent('home', 'services', 'image', ''); ?>
                <?php if (!empty($servicesImg)): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($servicesImg) ?>" alt="Container services" onerror="this.parentElement.classList.add('placeholder-img')">
                <?php else: ?>
                    <div class="placeholder-img"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Products Section -->
<?php
// Fetch product images for size cards
$sizeImages = [];
try {
    foreach (['10ft', '20ft', '40ft', 'High Cube'] as $sz) {
        $stmt = $pdo->prepare("SELECT image FROM products WHERE size LIKE ? AND image IS NOT NULL AND image != '' LIMIT 1");
        $stmt->execute(["%$sz%"]);
        $row = $stmt->fetch();
        $sizeImages[$sz] = $row ? $row['image'] : null;
    }
} catch (Exception $e) {}
?>
<section class="section bg-light">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Our Products</div>
            <h2>Premium Container Solutions</h2>
            <p>We offer a range of shipping container sizes to suit every project, from compact storage to large-scale modular builds.</p>
        </div>
        <div class="products-grid">
            <div class="product-card fade-in">
                <div class="product-icon">
                    <?php if ($sizeImages['10ft']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($sizeImages['10ft']) ?>" alt="10ft Container">
                    <?php else: ?>
                        <span>10ft</span>
                    <?php endif; ?>
                </div>
                <h4>10ft Containers</h4>
                <p>Compact and versatile for small spaces</p>
                <a href="<?= BASE_URL ?>/products#10ft" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
            <div class="product-card fade-in">
                <div class="product-icon">
                    <?php if ($sizeImages['20ft']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($sizeImages['20ft']) ?>" alt="20ft Container">
                    <?php else: ?>
                        <span>20ft</span>
                    <?php endif; ?>
                </div>
                <h4>20ft Containers</h4>
                <p>Most popular size for various applications</p>
                <a href="<?= BASE_URL ?>/products#20ft" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
            <div class="product-card fade-in">
                <div class="product-icon">
                    <?php if ($sizeImages['40ft']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($sizeImages['40ft']) ?>" alt="40ft Container">
                    <?php else: ?>
                        <span>40ft</span>
                    <?php endif; ?>
                </div>
                <h4>40ft Containers</h4>
                <p>Maximum space for large projects</p>
                <a href="<?= BASE_URL ?>/products#40ft" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
            <div class="product-card fade-in">
                <div class="product-icon">
                    <?php if ($sizeImages['High Cube']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($sizeImages['High Cube']) ?>" alt="High Cube Container">
                    <?php else: ?>
                        <span>HC</span>
                    <?php endif; ?>
                </div>
                <h4>High Cube</h4>
                <p>Extra height for specialized needs</p>
                <a href="<?= BASE_URL ?>/products#highcube" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
        </div>
        <div class="text-center" style="margin-top: var(--space-2xl);">
            <a href="<?= BASE_URL ?>/products" class="btn btn-outline-primary">Explore All Products</a>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Our Process</div>
            <h2>From Concept to Completion</h2>
            <p>A streamlined four-step process to bring your container project to life.</p>
        </div>
        <div class="process-grid">
            <div class="process-step fade-in">
                <div class="step-number">1</div>
                <h4>Consultation</h4>
                <p>We listen to your ideas, understand your needs, and recommend the most suitable container solution.</p>
            </div>
            <div class="process-step fade-in">
                <div class="step-number">2</div>
                <h4>Design</h4>
                <p>Our team prepares a customized concept and fabrication plan tailored to your project.</p>
            </div>
            <div class="process-step fade-in">
                <div class="step-number">3</div>
                <h4>Fabrication</h4>
                <p>Experienced fabricators transform your container into a durable, functional, and attractive space.</p>
            </div>
            <div class="process-step fade-in">
                <div class="step-number">4</div>
                <h4>Delivery & Installation</h4>
                <p>We safely transport and install your completed project at your preferred location.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item fade-in">
                <div class="number">500<span>+</span></div>
                <div class="label">Projects Completed</div>
            </div>
            <div class="stat-item fade-in">
                <div class="number">10<span>+</span></div>
                <div class="label">Years Experience</div>
            </div>
            <div class="stat-item fade-in">
                <div class="number">50<span>+</span></div>
                <div class="label">Team Members</div>
            </div>
            <div class="stat-item fade-in">
                <div class="number">100<span>%</span></div>
                <div class="label">Client Satisfaction</div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section bg-light">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Testimonials</div>
            <h2>What Our Clients Say</h2>
            <p>Trusted by hundreds of clients across Kenya for reliable container solutions.</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card fade-in">
                <div class="testimonial-content">
                    Kimnest Containers delivered our office container ahead of schedule and the quality was outstanding. Their team was professional from start to finish.
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">JM</div>
                    <div class="testimonial-info">
                        <h5>James Mwangi</h5>
                        <span>Construction Manager, BuildRight Ltd</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card fade-in">
                <div class="testimonial-content">
                    We converted two 40ft containers into a modern retail shop. The craftsmanship and attention to detail exceeded our expectations. Highly recommended!
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AN</div>
                    <div class="testimonial-info">
                        <h5>Aisha Njeri</h5>
                        <span>Owner, TrendSpot Retail</span>
                    </div>
                </div>
            </div>
            <div class="testimonial-card fade-in">
                <div class="testimonial-content">
                    From the initial consultation to final installation, the entire process was smooth. Our container home is beautiful and exactly what we envisioned.
                </div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">PO</div>
                    <div class="testimonial-info">
                        <h5>Peter Ochieng</h5>
                        <span>Homeowner, Kisumu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section cta-section">
    <div class="container">
        <div class="cta-content zoom-in">
            <h2><?= htmlspecialchars(getContent('home', 'cta', 'title', 'Ready to Bring Your Vision to Life?')) ?></h2>
            <p><?= htmlspecialchars(getContent('home', 'cta', 'description', 'Whether you\'re planning a modern container home, portable office, commercial shop, or custom modular solution, Kimnest Containers is ready to make it happen.')) ?></p>
            <div class="cta-btns">
                <a href="<?= BASE_URL ?><?= htmlspecialchars(getContent('home', 'cta', 'btn1_link', '/request-quote')) ?>" class="btn btn-primary btn-lg"><?= htmlspecialchars(getContent('home', 'cta', 'btn1_text', 'Get a Free Quotation')) ?></a>
                <a href="tel:<?= getSetting('site_phone', SITE_PHONE) ?>" class="btn btn-outline btn-lg"><i class="fas fa-phone"></i> Call Our Team</a>
                <a href="https://wa.me/<?= getSetting('site_whatsapp', SITE_WHATSAPP) ?>" class="btn btn-whatsapp btn-lg" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
(function() {
    var track = document.getElementById('heroTrack');
    if (!track) return;
    var slides = track.children;
    var total = slides.length;
    if (total <= 1) return;
    var current = 0;
    var dots = document.querySelectorAll('.hero-carousel-dot');
    var speed = parseInt('<?= htmlspecialchars(getSetting('hero_animation_speed', '4000')) ?>') || 4000;
    var autoInterval;

    function update() {
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach(function(d, i) { d.classList.toggle('active', i === current); });
    }

    window.heroScroll = function(dir) {
        current = (current + dir + total) % total;
        update();
        resetAuto();
    };

    window.heroGoTo = function(idx) {
        current = idx;
        update();
        resetAuto();
    };

    function resetAuto() {
        clearInterval(autoInterval);
        autoInterval = setInterval(function() { heroScroll(1); }, speed);
    }

    resetAuto();
})();
</script>