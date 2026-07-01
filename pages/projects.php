<?php
$pageTitle = 'Projects';
include __DIR__ . '/../includes/header.php';

$projects = getProjectsByCategory();
$categories = ['Residential', 'Office', 'Retail', 'Restaurant', 'Educational', 'Healthcare'];
$category = isset($_GET['cat']) ? sanitize($_GET['cat']) : null;
if ($category) {
    $projects = getProjectsByCategory($category);
}

$heroBg = getContent('projects', 'hero', 'bg_image', '');
$heroOverlay = getContent('projects', 'hero', 'overlay_color', '#1a2e4a');
$heroDesc = getContent('projects', 'hero', 'description', '');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1>Our Projects</h1>
        <?php if (!empty($heroDesc)): ?>
            <p class="page-banner__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <?php endif; ?>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span> <span>Projects</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="projects-layout">
            <aside class="projects-sidebar">
                <h3>Categories</h3>
                <ul class="projects-sidebar__list">
                    <li><a href="<?= BASE_URL ?>/projects" class="<?= !$category ? 'active' : '' ?>">All Projects</a></li>
                    <?php foreach ($categories as $cat): ?>
                        <li><a href="<?= BASE_URL ?>/projects?cat=<?= urlencode($cat) ?>" class="<?= $category === $cat ? 'active' : '' ?>"><?= $cat ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div class="projects-main">
                <?php if (empty($projects)): ?>
                    <div class="projects-empty">
                        <i class="fas fa-images"></i>
                        <h3>No Projects Found</h3>
                        <p>We're currently updating our portfolio. Check back soon!</p>
                    </div>
                <?php else: ?>
                    <div class="projects-grid projects-grid--2col">
                        <?php foreach ($projects as $project): ?>
                            <a href="<?= BASE_URL ?>/project?slug=<?= htmlspecialchars($project['slug']) ?>" class="project-card fade-in">
                                <div class="project-image">
                                    <?php
                                    $images = getProjectImages($project['id']);
                                    if (!empty($images)):
                                    ?>
                                        <img src="<?= BASE_URL ?>/<?= $images[0]['image'] ?>" alt="<?= $project['title'] ?>">
                                    <?php else: ?>
                                        <div class="project-image--placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="project-category"><?= $project['category'] ?></span>
                                </div>
                                <div class="project-content">
                                    <h4><?= $project['title'] ?></h4>
                                    <p><?= truncate($project['description'], 120) ?></p>
                                    <div class="project-meta">
                                        <?php if ($project['location']): ?>
                                            <span><i class="fas fa-map-marker-alt"></i> <?= $project['location'] ?></span>
                                        <?php endif; ?>
                                        <?php if ($project['completed_date']): ?>
                                            <span><i class="fas fa-calendar"></i> <?= formatDate($project['completed_date']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Have a Project in Mind?</h2>
            <p>Whether you're starting with a simple idea or detailed architectural plans, our team is ready to turn your vision into reality.</p>
            <div class="cta-btns">
                <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary btn-lg">Get a Free Estimate</a>
                <a href="<?= BASE_URL ?>/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
