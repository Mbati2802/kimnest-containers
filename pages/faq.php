<?php
$pageTitle = 'FAQ';
include __DIR__ . '/../includes/header.php';

$heroBg = getContent('faq', 'hero', 'bg_image', '');
$heroOverlay = getContent('faq', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('faq', 'hero', 'description', '');

$categories = getFAQCategories();
$allFaqs = getFAQs();
$activeCat = isset($_GET['cat']) ? sanitize($_GET['cat']) : (!empty($categories) ? $categories[0] : '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>FAQ</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="faq-layout">
            <aside class="faq-sidebar">
                <?php foreach ($categories as $i => $cat): ?>
                    <button class="faq-sidebar__item <?= $i === 0 || $activeCat === $cat ? 'active' : '' ?>" data-category="<?= htmlspecialchars($cat) ?>" onclick="filterFAQ(this)">
                        <span><?= htmlspecialchars($cat) ?></span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                <?php endforeach; ?>
            </aside>

            <div class="faq-content">
                <?php foreach ($allFaqs as $faq): ?>
                    <div class="faq-accordion__item" data-category="<?= htmlspecialchars($faq['category']) ?>" style="<?= $faq['category'] !== $activeCat ? 'display:none;' : '' ?>">
                        <div class="faq-accordion__header" onclick="toggleFaqAccordion(this)">
                            <span class="faq-accordion__question"><?= htmlspecialchars($faq['question']) ?></span>
                            <span class="faq-accordion__icon"><i class="fas fa-plus"></i></span>
                        </div>
                        <div class="faq-accordion__body">
                            <p><?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($allFaqs)): ?>
                    <div class="faq-empty">
                        <i class="fas fa-search"></i>
                        <p>No FAQs found. Check back soon.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="faq-cta fade-in">
            <h3>Still Have Questions?</h3>
            <p>Can't find the answer you're looking for? Our team is ready to help.</p>
            <div class="faq-cta__btns">
                <a href="<?= BASE_URL ?>/contact" class="btn btn-primary"><i class="fas fa-envelope"></i> Contact Us</a>
                <a href="https://wa.me/<?= getSetting('site_whatsapp', SITE_WHATSAPP) ?>" class="btn btn-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
            </div>
        </div>
    </div>
</section>

<script>
function filterFAQ(btn) {
    var cat = btn.getAttribute('data-category');
    document.querySelectorAll('.faq-sidebar__item').forEach(function(el) { el.classList.remove('active'); });
    btn.classList.add('active');
    document.querySelectorAll('.faq-accordion__item').forEach(function(el) {
        el.style.display = el.getAttribute('data-category') === cat ? '' : 'none';
        el.classList.remove('active');
    });
}

function toggleFaqAccordion(el) {
    var item = el.closest('.faq-accordion__item');
    var isActive = item.classList.contains('active');
    document.querySelectorAll('.faq-accordion__item').forEach(function(el) { el.classList.remove('active'); });
    if (!isActive) item.classList.add('active');
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
