<?php
$pageTitle = 'Contact Us';
include __DIR__ . '/../includes/header.php';
$flash = getFlash();

$heroBg = getContent('contact', 'hero', 'bg_image', '');
$heroOverlay = getContent('contact', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('contact', 'hero', 'description', '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1><?= htmlspecialchars(getContent('contact', 'hero', 'overline', 'Contact Us')) ?></h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>Contact Us</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-header">
            <h2 class="contact-header__title">CONTACT US</h2>
            <p class="contact-header__desc">If you have any questions, please feel free to get in touch with us via phone, text, email, the form below, or even on social media!</p>
        </div>

        <div class="contact-two-col">
            <div class="contact-form-card">
                <h3>GET IN TOUCH</h3>

                <?php if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?>" style="margin-bottom:16px;padding:10px 14px;font-size:13px;"><?= $flash['message'] ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/api/contact_handler.php" method="POST" data-ajax class="form--compact">
                    <div class="form-row">
                        <div class="form-group">
                            <label>NAME</label>
                            <input type="text" name="full_name" placeholder="Enter your name*" required>
                        </div>
                        <div class="form-group">
                            <label>PHONE NUMBER</label>
                            <input type="tel" name="phone" placeholder="Enter your phone number*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>EMAIL</label>
                        <input type="email" name="email" placeholder="Enter your email*" required>
                    </div>

                    <div class="form-group">
                        <label>SUBJECT</label>
                        <input type="text" name="subject" placeholder="Subject of your message*" required>
                    </div>

                    <div class="form-group">
                        <label>YOUR MESSAGE</label>
                        <textarea name="message" rows="6" required placeholder="Tell us about your inquiry..."></textarea>
                    </div>

                    <button type="submit" class="btn-contact-submit">SEND MESSAGE</button>
                </form>
            </div>

            <div class="contact-right-col">
                <div class="contact-info-card-new">
                    <h3>CONTACT INFORMATION</h3>

                    <div class="contact-info__item">
                        <div class="contact-info__icon"><i class="fas fa-phone"></i></div>
                        <div>
                            <span class="contact-info__label">PHONE</span>
                            <span class="contact-info__value"><?= htmlspecialchars(getSetting('site_phone', SITE_PHONE)) ?></span>
                        </div>
                    </div>

                    <div class="contact-info__item">
                        <div class="contact-info__icon contact-info__icon--pin"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <span class="contact-info__label">ADDRESS</span>
                            <span class="contact-info__value"><?= htmlspecialchars(getSetting('site_address', 'Nairobi, Kenya')) ?></span>
                        </div>
                    </div>

                    <div class="contact-info__item">
                        <div class="contact-info__icon contact-info__icon--email"><i class="fas fa-envelope"></i></div>
                        <div>
                            <span class="contact-info__label">EMAIL</span>
                            <span class="contact-info__value"><?= htmlspecialchars(getSetting('site_email', SITE_EMAIL)) ?></span>
                        </div>
                    </div>
                </div>

                <div class="contact-info-card-new">
                    <h3>BUSINESS HOURS</h3>
                    <div class="contact-hours">
                        <div class="contact-hours__item">
                            <span class="contact-hours__day">MONDAY - FRIDAY</span>
                            <span class="contact-hours__time">9:00 am - 8:00 pm</span>
                        </div>
                        <div class="contact-hours__item">
                            <span class="contact-hours__day">SATURDAY</span>
                            <span class="contact-hours__time">9:00 am - 6:00 pm</span>
                        </div>
                        <div class="contact-hours__item">
                            <span class="contact-hours__day">SUNDAY</span>
                            <span class="contact-hours__time">9:00 am - 5:00 pm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-map fade-in">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.346540478598!2d36.8172235!3d-1.2863813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f1733c3e46a0d%3A0x7b0e5c7e5c7e5c7e!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2sus!4v1234567890" width="100%" height="350" style="border:0;border-radius:var(--radius-md);" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
