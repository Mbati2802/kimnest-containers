<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Manager | <?= SITE_NAME ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
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
                <a href="<?= BASE_URL ?>/admin/content.php"><i class="fas fa-edit"></i> Site Content</a>
                <a href="<?= BASE_URL ?>/admin/settings.php"><i class="fas fa-cog"></i> Site Settings</a>
                <a href="<?= BASE_URL ?>/admin/media.php" class="active"><i class="fas fa-photo-video"></i> Media</a>
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
                <h1>Media Manager</h1>
            </div>

            <div class="upload-area" id="uploadArea">
                <i class="fas fa-cloud-upload-alt"></i>
                <h3>Upload Images</h3>
                <p>Drag & drop files here or click to browse</p>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-plus"></i> Select Files
                </button>
                <input type="file" id="fileInput" multiple accept="image/*" style="display:none">
                <div class="progress-bar" id="progressBar">
                    <div class="fill" id="progressFill"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Uploaded Images <span class="media-count" id="mediaCount"></span></h3>
                    <button type="button" class="btn btn-outline btn-sm" onclick="loadImages()"><i class="fas fa-sync-alt"></i> Refresh</button>
                </div>
                <div class="card-body">
                    <div id="imageGrid" class="image-grid">
                        <div class="empty-state" style="grid-column:1/-1;">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Loading images...</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const progressBar = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');
    const imageGrid = document.getElementById('imageGrid');

    function showToast(message, duration = 3000) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), duration);
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => { uploadArea.classList.remove('dragover'); });
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) uploadFiles(e.dataTransfer.files);
    });
    uploadArea.addEventListener('click', (e) => {
        if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'I') fileInput.click();
    });
    fileInput.addEventListener('change', () => { if (fileInput.files.length > 0) uploadFiles(fileInput.files); });

    function uploadFiles(files) {
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) formData.append('files[]', files[i]);
        formData.append('action', 'upload');
        progressBar.style.display = 'block';
        progressFill.style.width = '0%';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= BASE_URL ?>/api/media_handler.php?action=upload', true);
        xhr.upload.onprogress = (e) => { if (e.lengthComputable) progressFill.style.width = Math.round((e.loaded / e.total) * 100) + '%'; };
        xhr.onload = () => {
            progressBar.style.display = 'none';
            try {
                const result = JSON.parse(xhr.responseText);
                if (result.success) { showToast(result.message); loadImages(); }
                else showToast(result.errors ? result.errors.join(', ') : result.message);
            } catch (e) { showToast('Upload failed. Please try again.'); }
        };
        xhr.onerror = () => { progressBar.style.display = 'none'; showToast('Upload failed. Network error.'); };
        xhr.send(formData);
    }

    function loadImages() {
        imageGrid.innerHTML = '<div class="empty-state" style="grid-column:1/-1;"><i class="fas fa-spinner fa-spin"></i><p>Loading...</p></div>';
        fetch('<?= BASE_URL ?>/api/media_handler.php?action=list')
            .then(r => r.json())
            .then(data => {
                if (data.success && data.images.length > 0) {
                    document.getElementById('mediaCount').textContent = '(' + data.images.length + ' files)';
                    let html = '';
                    data.images.forEach(img => {
                        html += '<div class="image-card" data-filename="' + img.filename + '"><div class="actions"><button class="btn-copy" onclick="copyUrl(\'' + img.url + '\')" title="Copy URL"><i class="fas fa-link"></i></button><button class="btn-delete" onclick="deleteImage(\'' + img.filename + '\')" title="Delete"><i class="fas fa-trash"></i></button></div><img src="' + img.url + '" alt="' + img.filename + '" onclick="copyUrl(\'' + img.url + '\')"><div class="info"><span class="filename" title="' + img.filename + '">' + img.filename + '</span><span class="filesize">' + formatSize(img.size) + '</span></div></div>';
                    });
                    imageGrid.innerHTML = html;
                } else {
                    document.getElementById('mediaCount').textContent = '';
                    imageGrid.innerHTML = '<div class="empty-state" style="grid-column:1/-1;"><i class="fas fa-image"></i><p>No images uploaded yet.</p></div>';
                }
            })
            .catch(() => { imageGrid.innerHTML = '<div class="empty-state" style="grid-column:1/-1;"><i class="fas fa-exclamation-triangle"></i><p>Failed to load images.</p></div>'; });
    }

    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(() => { showToast('URL copied to clipboard!'); }).catch(() => {
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showToast('URL copied to clipboard!');
        });
    }

    function deleteImage(filename) {
        if (!confirm('Delete this image? This cannot be undone.')) return;
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('filename', filename);
        fetch('<?= BASE_URL ?>/api/media_handler.php?action=delete', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.success) { showToast('Image deleted.'); loadImages(); } else showToast(data.message || 'Failed to delete.'); })
            .catch(() => showToast('Failed to delete image.'));
    }

    loadImages();
    </script>
</body>
</html>
