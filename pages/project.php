<?php
require_once __DIR__ . '/../includes/db.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$project = $slug ? getProjectBySlug($slug) : null;

if (!$project) {
    header('Location: ' . BASE_URL . '/projects');
    exit;
}

$pageTitle = $project['title'];
$images = getProjectImages($project['id']);
include __DIR__ . '/../includes/header.php';

$heroBg = getContent('projects', 'hero', 'bg_image', '');
$heroOverlay = getContent('projects', 'hero', 'overlay_color', '#1a2e4a');
?>

<section class="page-banner page-banner--tall" <?php if (!empty($heroBg)): ?>style="background-image: linear-gradient(<?= $heroOverlay ?>dd, <?= $heroOverlay ?>ee), url(<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>); background-size: cover; background-position: center;"<?php endif; ?>>
    <div class="container">
        <h1><?= htmlspecialchars($project['title']) ?></h1>
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Home</a> <span>/</span>
            <a href="<?= BASE_URL ?>/projects">Projects</a> <span>/</span>
            <span><?= htmlspecialchars($project['title']) ?></span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="project-detail">
            <div class="project-detail__main">
                <?php if (!empty($images)): ?>
                    <div class="project-detail__gallery">
                        <div class="project-detail__hero-img">
                            <img src="<?= BASE_URL ?>/<?= $images[0]['image'] ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                        </div>
                        <?php if (count($images) > 1): ?>
                            <div class="project-detail__thumbs">
                                <?php foreach ($images as $img): ?>
                                    <div class="project-detail__thumb" onclick="document.querySelector('.project-detail__hero-img img').src='<?= BASE_URL ?>/<?= $img['image'] ?>'">
                                        <img src="<?= BASE_URL ?>/<?= $img['image'] ?>" alt="<?= htmlspecialchars($img['caption'] ?? '') ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="project-detail__description">
                    <?php if ($project['full_description']): ?>
                        <?= nl2br(htmlspecialchars($project['full_description'])) ?>
                    <?php else: ?>
                        <?= nl2br(htmlspecialchars($project['description'])) ?>
                    <?php endif; ?>
                </div>

                <?php if ($project['client_testimonial']): ?>
                    <div class="project-detail__testimonial">
                        <div class="testimonial-icon"><i class="fas fa-quote-left"></i></div>
                        <blockquote><?= htmlspecialchars($project['client_testimonial']) ?></blockquote>
                        <?php if ($project['client_name']): ?>
                            <cite>— <?= htmlspecialchars($project['client_name']) ?></cite>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="project-detail__sidebar">
                <div class="project-detail__info-card">
                    <h3>Project Details</h3>
                    <dl>
                        <dt>Category</dt>
                        <dd><?= htmlspecialchars($project['category']) ?></dd>

                        <?php if ($project['location']): ?>
                            <dt>Location</dt>
                            <dd><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($project['location']) ?></dd>
                        <?php endif; ?>

                        <?php if ($project['completed_date']): ?>
                            <dt>Completed</dt>
                            <dd><i class="fas fa-calendar"></i> <?= formatDate($project['completed_date'], 'F Y') ?></dd>
                        <?php endif; ?>

                        <dt>Status</dt>
                        <dd><span class="badge badge--<?= $project['status'] ?>"><?= ucfirst($project['status']) ?></span></dd>
                    </dl>
                </div>

                <div class="project-detail__cta-card">
                    <h3>Interested in a Similar Project?</h3>
                    <p>Let us help you bring your vision to life with our expert container solutions.</p>
                    <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary btn-block">Get a Free Quote</a>
                    <a href="<?= BASE_URL ?>/projects" class="btn btn-outline btn-block">View All Projects</a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
