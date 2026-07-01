<?php
$pageTitle = 'Services';
include __DIR__ . '/../includes/header.php';

$heroBg = getContent('services', 'hero', 'bg_image', '');
$heroOverlay = getContent('services', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('services', 'hero', 'description', 'We offer comprehensive container solutions that combine functionality, innovation, and exceptional craftsmanship.');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1>Our Services</h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>Services</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">What We Do</div>
            <h2>Innovative Container Solutions Designed Around Your Needs</h2>
            <p>We offer comprehensive container solutions that combine functionality, innovation, and exceptional craftsmanship.</p>
        </div>
        
        <div id="sales" style="margin-bottom: var(--space-4xl);">
            <div class="editorial fade-left">
                <div class="editorial-content">
                    <div class="overline">Container Sales</div>
                    <h2>Premium Quality Containers</h2>
                    <p>We supply premium-quality new and used shipping containers in a variety of sizes and specifications to suit different residential, commercial, and industrial applications.</p>
                    <p>Whether you require a standard storage container or a specialized unit ready for modification, we ensure every container meets high standards of quality and durability.</p>
                    <ul class="service-list">
                        <li>10ft Containers</li>
                        <li>20ft Containers</li>
                        <li>40ft Containers</li>
                        <li>High Cube Containers</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary">Request Container Pricing</a>
                </div>
                <div class="editorial-image">
                    <?php $salesImg = getContent('services', 'sales', 'image', ''); ?>
                    <?php if (!empty($salesImg)): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($salesImg) ?>" alt="Container Sales" onerror="this.parentElement.classList.add('placeholder-img')">
                    <?php else: ?>
                        <div class="placeholder-img"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div id="fabrication" style="margin-bottom: var(--space-4xl);">
            <div class="editorial editorial--reverse fade-right">
                <div class="editorial-content">
                    <div class="overline">Fabrication</div>
                    <h2>Custom Container Fabrication</h2>
                    <p>Our fabrication service transforms standard shipping containers into fully functional spaces tailored to your unique requirements.</p>
                    <p>Using advanced fabrication techniques and premium finishing materials, we create modern, attractive, and highly functional structures.</p>
                    <ul class="service-list">
                        <li>Offices & Residential Homes</li>
                        <li>Retail Shops & Restaurants</li>
                        <li>Classrooms & Clinics</li>
                        <li>Security Cabins & Studios</li>
                    </ul>
                    <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary">Start Your Project</a>
                </div>
                <div class="editorial-image">
                    <?php $fabImg = getContent('services', 'fabrication', 'image', ''); ?>
                    <?php if (!empty($fabImg)): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($fabImg) ?>" alt="Container Fabrication" onerror="this.parentElement.classList.add('placeholder-img')">
                    <?php else: ?>
                        <div class="placeholder-img"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div id="modifications" style="margin-bottom: var(--space-4xl);">
            <div class="editorial fade-left">
                <div class="editorial-content">
                    <div class="overline">Modifications</div>
                    <h2>Container Modifications</h2>
                    <p>Need to customize an existing container? Our modification services allow clients to transform standard containers into practical, comfortable, and visually appealing spaces.</p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 14px; color: var(--gray-500); margin-bottom: var(--space-lg);">
                        <span>Door Installation</span>
                        <span>Windows & Glass Fronts</span>
                        <span>Electrical Wiring</span>
                        <span>Plumbing</span>
                        <span>Interior Partitioning</span>
                        <span>Ceiling & Flooring</span>
                        <span>Roofing & Insulation</span>
                        <span>Painting & Branding</span>
                        <span>Kitchen Installation</span>
                        <span>Bathroom Installation</span>
                        <span>Lighting Systems</span>
                        <span>AC Preparation</span>
                    </div>
                    <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary">Customize Your Container</a>
                </div>
                <div class="editorial-image">
                    <?php $modImg = getContent('services', 'modifications', 'image', ''); ?>
                    <?php if (!empty($modImg)): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($modImg) ?>" alt="Container Modifications" onerror="this.parentElement.classList.add('placeholder-img')">
                    <?php else: ?>
                        <div class="placeholder-img"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div id="delivery">
            <div class="editorial editorial--reverse fade-right">
                <div class="editorial-content">
                    <div class="overline">Delivery</div>
                    <h2>Delivery & Installation</h2>
                    <p>We understand that timely delivery is essential to the success of your project. Our logistics team coordinates safe transportation and professional installation of completed container structures to your desired location across Kenya.</p>
                    <p>Our installation services ensure that your container is positioned correctly, secured properly, and ready for immediate use.</p>
                    <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary">Book Delivery & Installation</a>
                </div>
                <div class="editorial-image">
                    <?php $delImg = getContent('services', 'delivery', 'image', ''); ?>
                    <?php if (!empty($delImg)): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($delImg) ?>" alt="Delivery and Installation" onerror="this.parentElement.classList.add('placeholder-img')">
                    <?php else: ?>
                        <div class="placeholder-img"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Additional Services</div>
            <h2>More Solutions We Offer</h2>
            <p>Specialized container solutions for specific industry needs.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-home"></i></div>
                <h4>Container Homes</h4>
                <p>Modern, stylish, and affordable homes built from shipping containers.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-briefcase"></i></div>
                <h4>Office Containers</h4>
                <p>Professional workspaces equipped with modern finishes.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-store"></i></div>
                <h4>Container Shops</h4>
                <p>Attractive retail spaces for your business.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-utensils"></i></div>
                <h4>Restaurants & Cafés</h4>
                <p>Unique hospitality spaces with creativity and practicality.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-graduation-cap"></i></div>
                <h4>Classrooms & Libraries</h4>
                <p>Affordable, scalable learning environments for schools and institutions.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-heartbeat"></i></div>
                <h4>Healthcare Clinics</h4>
                <p>Quick-deploy medical facilities and mobile clinics for communities.</p>
            </div>
        </div>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Have a Unique Project in Mind?</h2>
            <p>Our design team works closely with clients to develop customized container projects that reflect specific operational, architectural, or lifestyle requirements.</p>
            <div class="cta-btns">
                <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary btn-lg">Discuss Your Project</a>
                <a href="https://wa.me/<?= getSetting('site_whatsapp', SITE_WHATSAPP) ?>" class="btn btn-whatsapp btn-lg" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
