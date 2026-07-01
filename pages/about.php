<?php
$pageTitle = 'About Us';
include __DIR__ . '/../includes/header.php';

$heroBg = getContent('about', 'hero', 'bg_image', '');
$heroOverlay = getContent('about', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('about', 'hero', 'description', '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1>About Us</h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>About Us</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="editorial fade-left">
            <div class="editorial-content">
                <div class="overline">Our Story</div>
                <h2>Building Dreams Through Innovative Container Solutions</h2>
                <p>At Kimnest Containers, we believe that every space has the potential to inspire, empower, and transform lives. What began as a vision to provide affordable and innovative container solutions has grown into a trusted company dedicated to delivering high-quality container sales, fabrication, and customized construction services across Kenya.</p>
                <p>Our expertise lies in transforming ordinary shipping containers into extraordinary spaces that serve a wide range of purposes—from modern homes and executive offices to retail shops, restaurants, classrooms, clinics, security cabins, and specialized commercial facilities.</p>
                <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary">Start Your Project</a>
            </div>
            <div class="editorial-image">
                <?php $aboutPageImg = getContent('about', 'hero', 'image', ''); ?>
                <?php if (!empty($aboutPageImg)): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($aboutPageImg) ?>" alt="About Kimnest Containers" onerror="this.parentElement.classList.add('placeholder-img')">
                <?php else: ?>
                    <div class="placeholder-img"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Mission & Vision</div>
            <h2>What Drives Us Forward</h2>
        </div>
        <div class="features-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-bullseye"></i></div>
                <h4>Our Mission</h4>
                <p>To provide innovative, durable, and affordable container solutions that exceed customer expectations through exceptional craftsmanship, quality materials, and outstanding customer service.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-eye"></i></div>
                <h4>Our Vision</h4>
                <p>To become East Africa's most trusted and preferred provider of container sales, fabrication, and modular construction solutions by delivering excellence, innovation, and lasting value in every project.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Core Values</div>
            <h2>Principles That Guide Us</h2>
            <p>Our values define who we are and how we deliver for our clients.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-star"></i></div>
                <h4>Excellence</h4>
                <p>We strive for excellence in everything we do, ensuring every project meets the highest standards.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-handshake"></i></div>
                <h4>Integrity</h4>
                <p>We believe in honesty, transparency, and accountability throughout every stage of our relationship.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-lightbulb"></i></div>
                <h4>Innovation</h4>
                <p>We continuously embrace new ideas, modern fabrication techniques, and creative designs.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h4>Customer Focus</h4>
                <p>Our clients are at the center of our business. We listen carefully and deliver beyond expectations.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-gem"></i></div>
                <h4>Quality</h4>
                <p>We use premium materials and skilled craftsmanship to ensure every structure is built to last.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-clock"></i></div>
                <h4>Reliability</h4>
                <p>From consultation to delivery, clients can depend on us for timely and professional service.</p>
            </div>
        </div>
    </div>
</section>

<section class="section bg-light">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Why Choose Us</div>
            <h2>The Kimnest Advantage</h2>
            <p>We combine expertise, quality, and dedication to deliver exceptional results.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-user-tie"></i></div>
                <h4>Experienced Professionals</h4>
                <p>Our skilled fabrication team has the expertise to design and construct a wide variety of solutions.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-drafting-compass"></i></div>
                <h4>Tailor-Made Solutions</h4>
                <p>Every client has different requirements. We provide fully customized fabrication solutions.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-check-circle"></i></div>
                <h4>Premium Quality Materials</h4>
                <p>Quality is never compromised. We source durable containers and premium finishing materials.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-tags"></i></div>
                <h4>Competitive Pricing</h4>
                <p>Our efficient processes allow us to deliver high-quality projects at competitive prices.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
                <h4>Nationwide Service</h4>
                <p>Whether you're in Nairobi, Mombasa, Kisumu, or anywhere across Kenya, we deliver.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <h4>End-to-End Support</h4>
                <p>From consultation to installation and after-sales support, we provide a seamless experience.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header fade-in">
            <div class="overline">Industries</div>
            <h2>Industries We Serve</h2>
            <p>Container solutions tailored for diverse sectors across Kenya.</p>
        </div>
        <div class="features-grid" style="grid-template-columns: repeat(4, 1fr);">
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-home"></i></div><h4>Residential</h4></div>
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-building"></i></div><h4>Commercial</h4></div>
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-store"></i></div><h4>Retail</h4></div>
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-utensils"></i></div><h4>Hospitality</h4></div>
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-graduation-cap"></i></div><h4>Education</h4></div>
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-heartbeat"></i></div><h4>Healthcare</h4></div>
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-hard-hat"></i></div><h4>Construction</h4></div>
            <div class="feature-card fade-in"><div class="feature-icon"><i class="fas fa-warehouse"></i></div><h4>Industrial</h4></div>
        </div>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Let's Build Something Exceptional Together</h2>
            <p>Whether you're looking for a simple storage container or a fully customized container home, Kimnest Containers has the expertise to bring your ideas to life.</p>
            <div class="cta-btns">
                <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary btn-lg">Request a Free Consultation</a>
                <a href="<?= BASE_URL ?>/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
