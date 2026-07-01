<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_all_fields'])) {
    $page = sanitize($_POST['page_slug']);
    $fields = $_POST['fields'] ?? [];
    $fieldTypes = $_POST['field_types'] ?? [];
    $visibility = $_POST['visibility'] ?? [];

    foreach ($fields as $section => $sectionFields) {
        if (!is_array($sectionFields)) continue;
        foreach ($sectionFields as $key => $value) {
            $contentType = $fieldTypes[$section][$key] ?? 'text';
            $visible = (isset($visibility[$section][$key]) && $visibility[$section][$key] == '1') ? 1 : 0;

            if ($contentType !== 'textarea' && $contentType !== 'html') {
                $value = sanitize($value);
            }

            $stmt = $pdo->prepare("INSERT INTO site_content (page_slug, section_key, content_key, content_value, content_type, is_visible) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE content_value = ?, is_visible = ?");
            $stmt->execute([$page, $section, $key, $value, $contentType, $visible, $value, $visible]);
        }
    }

    header('Location: ' . BASE_URL . '/admin/content.php?page=' . urlencode($page) . '&saved=1');
    exit;
}

$page = isset($_GET['page']) ? sanitize($_GET['page']) : 'home';
$validPages = ['home', 'about', 'services', 'products', 'projects', 'blog', 'faq', 'contact', 'quote', 'cart'];
if (!in_array($page, $validPages)) $page = 'home';

$sections = $pdo->prepare("SELECT DISTINCT section_key FROM site_content WHERE page_slug = ? ORDER BY section_key");
$sections->execute([$page]);
$sections = $sections->fetchAll(PDO::FETCH_COLUMN);

$contentBySection = [];
foreach ($sections as $section) {
    $stmt = $pdo->prepare("SELECT * FROM site_content WHERE page_slug = ? AND section_key = ? ORDER BY id");
    $stmt->execute([$page, $section]);
    $contentBySection[$section] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Content | <?= SITE_NAME ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css?v=2" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js?v=2"></script>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="logo">Kim<span>nest</span></div>
                <div class="subtitle">Admin Panel</div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">Main</div>
                <a href="<?= BASE_URL ?>/admin/index.php"><i class="fas fa-th-large"></i> Dashboard</a>
                <div class="nav-section">Content</div>
                <a href="<?= BASE_URL ?>/admin/content.php" class="active"><i class="fas fa-edit"></i> Site Content</a>
                <a href="<?= BASE_URL ?>/admin/settings.php"><i class="fas fa-cog"></i> Site Settings</a>
                <a href="<?= BASE_URL ?>/admin/media.php"><i class="fas fa-photo-video"></i> Media</a>
                <div class="nav-section">Manage</div>
                <a href="<?= BASE_URL ?>/admin/quotes.php"><i class="fas fa-file-invoice"></i> Quotes <span class="badge"><?= $newQuotes ?></span></a>
                <a href="<?= BASE_URL ?>/admin/contacts.php"><i class="fas fa-envelope"></i> Contacts <span class="badge"><?= $unreadContacts ?></span></a>
                <a href="<?= BASE_URL ?>/admin/blog.php"><i class="fas fa-newspaper"></i> Blog Posts</a>
                <a href="<?= BASE_URL ?>/admin/products.php"><i class="fas fa-box"></i> Products</a>
                <a href="<?= BASE_URL ?>/admin/projects.php"><i class="fas fa-images"></i> Projects</a>
                <a href="<?= BASE_URL ?>/admin/newsletter.php"><i class="fas fa-paper-plane"></i> Newsletter</a>
                <a href="<?= BASE_URL ?>/admin/faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                <div class="nav-section">System</div>
                <a href="<?= BASE_URL ?>/admin/notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Site Content</h1>
            </div>

            <?php if (isset($_GET['saved'])): ?>
                <div class="alert alert-success">All content saved successfully.</div>
            <?php endif; ?>

            <div class="tabs">
                <a href="<?= BASE_URL ?>/admin/content.php?page=home" class="tab <?= $page === 'home' ? 'active' : '' ?>">Home</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=about" class="tab <?= $page === 'about' ? 'active' : '' ?>">About</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=services" class="tab <?= $page === 'services' ? 'active' : '' ?>">Services</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=products" class="tab <?= $page === 'products' ? 'active' : '' ?>">Products</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=projects" class="tab <?= $page === 'projects' ? 'active' : '' ?>">Projects</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=blog" class="tab <?= $page === 'blog' ? 'active' : '' ?>">Blog</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=faq" class="tab <?= $page === 'faq' ? 'active' : '' ?>">FAQ</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=contact" class="tab <?= $page === 'contact' ? 'active' : '' ?>">Contact</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=quote" class="tab <?= $page === 'quote' ? 'active' : '' ?>">Quote</a>
                <a href="<?= BASE_URL ?>/admin/content.php?page=cart" class="tab <?= $page === 'cart' ? 'active' : '' ?>">Cart</a>
            </div>

            <?php if (empty($sections)): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="empty-state">
                            <i class="fas fa-file-alt"></i>
                            <p>No content found for this page yet.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <form method="POST" id="contentForm">
                    <input type="hidden" name="save_all_fields" value="1">
                    <input type="hidden" name="page_slug" value="<?= htmlspecialchars($page) ?>">

                    <?php
                    $sectionDescriptions = [
                        'home' => [
                            'hero' => 'Hero section at the top of the homepage. Controls the main headline, description, buttons, and carousel images.',
                            'stats' => 'Stat cards that appear below the hero section.',
                            'about' => 'About editorial section on the homepage.',
                            'whyus' => 'Why Choose Us section with feature cards.',
                            'services' => 'Services editorial section on the homepage.',
                            'cta' => 'Call-to-action section at the bottom of the page.',
                        ],
                        'about' => [
                            'hero' => 'About page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'services' => [
                            'hero' => 'Services page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'products' => [
                            'hero' => 'Products page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'projects' => [
                            'hero' => 'Projects page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'blog' => [
                            'hero' => 'Blog page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'faq' => [
                            'hero' => 'FAQ page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'contact' => [
                            'hero' => 'Contact page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'quote' => [
                            'hero' => 'Quote page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                        'cart' => [
                            'hero' => 'Cart page hero — background image, overlay color, description, and breadcrumb.',
                        ],
                    ];
                    $descriptions = $sectionDescriptions[$page] ?? [];
                    ?>

                    <?php foreach ($contentBySection as $sectionKey => $fields): ?>
                        <div class="section-block">
                            <div class="section-header" onclick="toggleSection(this)">
                                <i class="fas fa-chevron-down"></i>
                                <h3><?= ucfirst(str_replace('_', ' ', $sectionKey)) ?></h3>
                                <span style="color:var(--admin-text-muted);font-size:12px;margin-left:auto;"><?= count($fields) ?> field(s)</span>
                            </div>
                            <?php if (!empty($descriptions[$sectionKey])): ?>
                                <div style="padding:10px 20px;background:#f0f4ff;font-size:12px;color:#4b5563;border-bottom:1px solid var(--admin-border);">
                                    <i class="fas fa-info-circle" style="color:var(--admin-primary);margin-right:6px;"></i><?= $descriptions[$sectionKey] ?>
                                </div>
                            <?php endif; ?>
                            <div class="section-body">
                                <?php foreach ($fields as $field): ?>
                                    <?php
                                    $ck = $field['content_key'];
                                    $sk = $sectionKey;
                                    $domId = $sk . '_' . $ck;
                                    ?>
                                    <div class="field-group">
                                        <div class="field-label">
                                            <?= ucfirst(str_replace('_', ' ', $ck)) ?>
                                            <span class="field-key"><?= htmlspecialchars($ck) ?></span>
                                            <span class="badge-type badge-<?= $field['content_type'] ?>"><?= $field['content_type'] ?></span>
                                            <input type="hidden" name="visibility[<?= htmlspecialchars($sk) ?>][<?= htmlspecialchars($ck) ?>]" id="vis_<?= $domId ?>" value="<?= (!isset($field['is_visible']) || $field['is_visible'] == 1) ? '1' : '0' ?>">
                                            <button type="button" class="visibility-toggle <?= (!isset($field['is_visible']) || $field['is_visible'] == 1) ? 'visible' : 'hidden' ?>" onclick="toggleVisibility(this, '<?= $domId ?>')" title="Toggle visibility">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>

                                        <?php if ($field['content_type'] === 'image'): ?>
                                            <div class="image-field">
                                                <div class="image-preview" id="preview_<?= $domId ?>">
                                                    <?php if (!empty($field['content_value'])): ?>
                                                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($field['content_value']) ?>" alt="Preview">
                                                    <?php else: ?>
                                                        <i class="fas fa-image"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="image-actions">
                                                    <input type="hidden" name="fields[<?= htmlspecialchars($sk) ?>][<?= htmlspecialchars($ck) ?>]" value="<?= htmlspecialchars($field['content_value']) ?>" id="field_<?= $domId ?>">
                                                    <input type="hidden" name="field_types[<?= htmlspecialchars($sk) ?>][<?= htmlspecialchars($ck) ?>]" value="image">
                                                    <input type="file" accept="image/*" style="display:none" id="file_<?= $domId ?>" onchange="previewImage(this, '<?= $domId ?>')">
                                                    <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('file_<?= $domId ?>').click()">
                                                        <i class="fas fa-upload"></i> Upload
                                                    </button>
                                                    <button type="button" class="btn btn-outline btn-sm" onclick="openMediaLibrary('<?= $domId ?>')">
                                                        <i class="fas fa-photo-video"></i> Library
                                                    </button>
                                                </div>
                                            </div>
                                        <?php elseif ($field['content_type'] === 'textarea' || $field['content_type'] === 'html'): ?>
                                            <textarea name="fields[<?= htmlspecialchars($sk) ?>][<?= htmlspecialchars($ck) ?>]" rows="4" class="rich-editor"><?= htmlspecialchars($field['content_value']) ?></textarea>
                                            <input type="hidden" name="field_types[<?= htmlspecialchars($sk) ?>][<?= htmlspecialchars($ck) ?>]" value="<?= $field['content_type'] ?>">
                                        <?php else: ?>
                                            <input type="text" name="fields[<?= htmlspecialchars($sk) ?>][<?= htmlspecialchars($ck) ?>]" value="<?= htmlspecialchars($field['content_value']) ?>">
                                            <input type="hidden" name="field_types[<?= htmlspecialchars($sk) ?>][<?= htmlspecialchars($ck) ?>]" value="text">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="save-bar">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Changes</button>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>

    <div class="modal-overlay" id="mediaModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-photo-video"></i> Media Library</h3>
                <button type="button" class="btn btn-link" onclick="closeMediaLibrary()" style="font-size:18px;padding:0;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="mediaModalGrid" class="modal-grid">
                    <p style="text-align:center;color:var(--admin-text-muted);grid-column:1/-1;">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleSection(el) {
        const block = el.closest('.section-block');
        const body = block.querySelector('.section-body');
        el.classList.toggle('collapsed');
        body.style.display = body.style.display === 'none' ? 'block' : 'none';
    }

    function toggleVisibility(btn, domId) {
        var input = document.getElementById('vis_' + domId);
        var icon = btn.querySelector('i');
        if (input.value === '1') {
            input.value = '0';
            btn.classList.remove('visible');
            btn.classList.add('hidden');
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.value = '1';
            btn.classList.remove('hidden');
            btn.classList.add('visible');
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function previewImage(input, domId) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview_' + domId).innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
            };
            reader.readAsDataURL(file);

            var formData = new FormData();
            formData.append('files[]', file);
            formData.append('action', 'upload');

            fetch('<?= BASE_URL ?>/api/media_handler.php?action=upload', { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success && data.uploaded && data.uploaded.length > 0) {
                        var uploadedUrl = data.uploaded[0].url;
                        var baseUrl = '<?= BASE_URL ?>/';
                        var storagePath = uploadedUrl;
                        if (uploadedUrl.startsWith(baseUrl)) storagePath = uploadedUrl.substring(baseUrl.length);
                        document.getElementById('field_' + domId).value = storagePath;
                    } else {
                        alert('Upload failed: ' + (data.errors ? data.errors.join(', ') : data.message));
                    }
                })
                .catch(function() { alert('Upload failed. Network error.'); });
        }
    }

    let currentMediaDomId = null;

    function openMediaLibrary(domId) {
        currentMediaDomId = domId;
        document.getElementById('mediaModal').classList.add('active');
        loadMediaLibrary();
    }

    function closeMediaLibrary() {
        document.getElementById('mediaModal').classList.remove('active');
        currentMediaDomId = null;
    }

    function loadMediaLibrary() {
        const grid = document.getElementById('mediaModalGrid');
        grid.innerHTML = '<p style="text-align:center;color:var(--admin-text-muted);grid-column:1/-1;">Loading...</p>';
        fetch('<?= BASE_URL ?>/api/media_handler.php?action=list&_t=' + Date.now())
            .then(r => r.json())
            .then(data => {
                if (data.success && data.images.length > 0) {
                    let html = '';
                    data.images.forEach(img => {
                        html += '<div class="media-item" onclick="selectMedia(\'' + img.url + '\', \'' + img.filename + '\')"><img src="' + img.url + '?_t=' + Date.now() + '" alt="' + img.filename + '"><div class="name">' + img.filename + '</div></div>';
                    });
                    grid.innerHTML = html;
                } else {
                    grid.innerHTML = '<p style="text-align:center;color:var(--admin-text-muted);grid-column:1/-1;">No images found.</p>';
                }
            })
            .catch(() => {
                grid.innerHTML = '<p style="text-align:center;color:var(--admin-text-muted);grid-column:1/-1;">Failed to load media.</p>';
            });
    }

    function selectMedia(url, filename) {
        if (currentMediaDomId) {
            const baseUrl = '<?= BASE_URL ?>/';
            let storagePath = url;
            if (url.startsWith(baseUrl)) storagePath = url.substring(baseUrl.length);
            document.getElementById('field_' + currentMediaDomId).value = storagePath;
            document.getElementById('preview_' + currentMediaDomId).innerHTML = '<img src="' + url + '" alt="Preview">';
        }
        closeMediaLibrary();
    }

    document.getElementById('mediaModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) closeMediaLibrary();
    });

    (function initQuillEditors() {
        if (window.__quillInitDone) return;
        window.__quillInitDone = true;

        var editors = document.querySelectorAll('.rich-editor');
        editors.forEach(function(textarea) {
            if (textarea.getAttribute('data-quill-init') === '1') return;
            textarea.setAttribute('data-quill-init', '1');

            var uniqueId = 'quill_' + Math.random().toString(36).substr(2, 9);
            var container = document.createElement('div');
            container.id = uniqueId;
            textarea.style.display = 'none';
            textarea.parentNode.insertBefore(container, textarea);

            var quill = new Quill('#' + uniqueId, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'Write content here...'
            });

            if (textarea.value) quill.root.innerHTML = textarea.value;

            var form = textarea.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    textarea.value = quill.root.innerHTML;
                });
            }

            textarea.quillInstance = quill;
        });
    })();
    </script>
</body>
</html>
