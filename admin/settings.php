<?php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$newQuotes = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'new'")->fetchColumn();
$unreadContacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE status = 'unread'")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    foreach ($_POST as $key => $value) {
        if ($key === 'save_settings') continue;
        $key = sanitize($key);
        $value = sanitize($value);
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES (?, ?, 'text') ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    header('Location: ' . BASE_URL . '/admin/settings.php?saved=1');
    exit;
}

$settings = $pdo->query("SELECT * FROM site_settings ORDER BY id")->fetchAll();
$settingsMap = [];
foreach ($settings as $s) {
    $settingsMap[$s['setting_key']] = $s['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings | <?= SITE_NAME ?> Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
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
                <a href="<?= BASE_URL ?>/admin/settings.php" class="active"><i class="fas fa-cog"></i> Site Settings</a>
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
                <a href="<?= BASE_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
                <a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Site Settings</h1>
            </div>

            <?php if (isset($_GET['saved'])): ?>
                <div class="alert alert-success">Settings saved successfully.</div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="save_settings" value="1">

                        <h3 style="font-size:14px;font-weight:600;margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--admin-primary);display:inline-block;">General</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="site_name">Site Name</label>
                                <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($settingsMap['site_name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="site_tagline">Tagline</label>
                                <input type="text" id="site_tagline" name="site_tagline" value="<?= htmlspecialchars($settingsMap['site_tagline'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_address">Address</label>
                            <input type="text" id="site_address" name="site_address" value="<?= htmlspecialchars($settingsMap['site_address'] ?? '') ?>">
                        </div>

                        <h3 style="font-size:14px;font-weight:600;margin:24px 0 16px;padding-bottom:8px;border-bottom:2px solid var(--admin-primary);display:inline-block;">Logo & Favicon</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Site Logo</label>
                                <div class="image-field">
                                    <div class="image-preview" id="logo-preview" style="width:120px;height:60px;background:#f9fafb;display:flex;align-items:center;justify-content:center;border:1px solid var(--admin-border);border-radius:6px;overflow:hidden;">
                                        <?php if (!empty($settingsMap['site_logo'])): ?>
                                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($settingsMap['site_logo']) ?>" style="max-width:100%;max-height:100%;object-fit:contain;">
                                        <?php else: ?>
                                            <i class="fas fa-image" style="color:#ccc;font-size:20px;"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="image-actions">
                                        <input type="hidden" name="site_logo" id="site_logo" value="<?= htmlspecialchars($settingsMap['site_logo'] ?? '') ?>">
                                        <label class="btn btn-sm btn-outline" style="cursor:pointer;"><i class="fas fa-upload"></i> Upload<input type="file" accept="image/*" style="display:none;" onchange="uploadSettingImage(this, 'site_logo', 'logo-preview')"></label>
                                        <?php if (!empty($settingsMap['site_logo'])): ?>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('site_logo').value='';document.getElementById('logo-preview').innerHTML='<i class=&quot;fas fa-image&quot; style=&quot;color:#ccc;font-size:20px;&quot;></i>'"><i class="fas fa-trash"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <small style="color:var(--admin-text-muted);font-size:11px;">Recommended: 200×50px, PNG with transparent background</small>
                            </div>
                            <div class="form-group">
                                <label>Favicon</label>
                                <div class="image-field">
                                    <div class="image-preview" id="favicon-preview" style="width:48px;height:48px;background:#f9fafb;display:flex;align-items:center;justify-content:center;border:1px solid var(--admin-border);border-radius:6px;overflow:hidden;">
                                        <?php if (!empty($settingsMap['site_favicon'])): ?>
                                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($settingsMap['site_favicon']) ?>" style="max-width:100%;max-height:100%;object-fit:contain;">
                                        <?php else: ?>
                                            <i class="fas fa-image" style="color:#ccc;font-size:20px;"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="image-actions">
                                        <input type="hidden" name="site_favicon" id="site_favicon" value="<?= htmlspecialchars($settingsMap['site_favicon'] ?? '') ?>">
                                        <label class="btn btn-sm btn-outline" style="cursor:pointer;"><i class="fas fa-upload"></i> Upload<input type="file" accept="image/*" style="display:none;" onchange="uploadSettingImage(this, 'site_favicon', 'favicon-preview')"></label>
                                        <?php if (!empty($settingsMap['site_favicon'])): ?>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('site_favicon').value='';document.getElementById('favicon-preview').innerHTML='<i class=&quot;fas fa-image&quot; style=&quot;color:#ccc;font-size:20px;&quot;></i>'"><i class="fas fa-trash"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <small style="color:var(--admin-text-muted);font-size:11px;">Recommended: 32×32px or 64×64px PNG/ICO</small>
                            </div>
                        </div>

                        <h3 style="font-size:14px;font-weight:600;margin:24px 0 16px;padding-bottom:8px;border-bottom:2px solid var(--admin-primary);display:inline-block;">Contact Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="site_email">Email Address</label>
                                <input type="text" id="site_email" name="site_email" value="<?= htmlspecialchars($settingsMap['site_email'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="site_phone">Phone Number</label>
                                <input type="text" id="site_phone" name="site_phone" value="<?= htmlspecialchars($settingsMap['site_phone'] ?? '') ?>">
                                <div class="hint">Include country code, e.g. +254...</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_whatsapp">WhatsApp Number</label>
                            <input type="text" id="site_whatsapp" name="site_whatsapp" value="<?= htmlspecialchars($settingsMap['site_whatsapp'] ?? '') ?>">
                            <div class="hint">Numbers only with country code, e.g. 254712345678</div>
                        </div>

                        <h3 style="font-size:14px;font-weight:600;margin:24px 0 16px;padding-bottom:8px;border-bottom:2px solid var(--admin-primary);display:inline-block;">Hero Carousel</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="hero_animation_speed">Animation Speed (ms)</label>
                                <input type="text" id="hero_animation_speed" name="hero_animation_speed" value="<?= htmlspecialchars($settingsMap['hero_animation_speed'] ?? '4000') ?>">
                                <div class="hint">Milliseconds between slides. e.g. 3000 = 3 seconds, 5000 = 5 seconds</div>
                            </div>
                        </div>

                        <h3 style="font-size:14px;font-weight:600;margin:24px 0 16px;padding-bottom:8px;border-bottom:2px solid var(--admin-primary);display:inline-block;">Floating Card Colors</h3>
                        <p style="font-size:12px;color:var(--admin-text-muted);margin-bottom:16px;">Customize each card individually. Use the color picker or type a hex value.</p>

                        <?php
                        $cardLabels = [1 => 'Card 1 — Projects Completed', 2 => 'Card 2 — Years Experience', 3 => 'Card 3 — Client Satisfaction'];
                        $cardFields = [
                            'bg' => 'Background',
                            'number_color' => 'Number Color',
                            'accent_color' => 'Accent (+ sign)',
                            'text_color' => 'Title Color',
                            'sub_color' => 'Subtitle Color',
                        ];
                        $cardDefaults = [
                            'bg' => '#ffffff',
                            'number_color' => '#3e7ac5',
                            'accent_color' => '#f5c140',
                            'text_color' => '#1a1d23',
                            'sub_color' => '#9ba3af',
                        ];
                        foreach ($cardLabels as $ci => $label):
                        ?>
                        <div style="background:var(--admin-bg);border:1px solid var(--admin-border);border-radius:8px;padding:16px;margin-bottom:16px;">
                            <div style="font-size:13px;font-weight:600;color:var(--admin-text);margin-bottom:12px;"><?= $label ?></div>
                            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;">
                            <?php foreach ($cardFields as $fk => $fl): ?>
                                <?php $inputName = "card{$ci}_{$fk}"; $defaultVal = $cardDefaults[$fk]; $currentVal = htmlspecialchars($settingsMap[$inputName] ?? $defaultVal); ?>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="<?= $inputName ?>" style="font-size:11px;"><?= $fl ?></label>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <div class="pickr" id="pickr_<?= $inputName ?>" data-field="<?= $inputName ?>" data-default="<?= $defaultVal ?>"></div>
                                        <input type="text" id="<?= $inputName ?>" name="<?= $inputName ?>" value="<?= $currentVal ?>" style="width:100%;font-size:12px;" class="color-hex-input">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <h3 style="font-size:14px;font-weight:600;margin:24px 0 16px;padding-bottom:8px;border-bottom:2px solid var(--admin-primary);display:inline-block;">Social Media Links</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="facebook_url">Facebook URL</label>
                                <input type="text" id="facebook_url" name="facebook_url" value="<?= htmlspecialchars($settingsMap['facebook_url'] ?? '#') ?>" placeholder="https://facebook.com/...">
                            </div>
                            <div class="form-group">
                                <label for="instagram_url">Instagram URL</label>
                                <input type="text" id="instagram_url" name="instagram_url" value="<?= htmlspecialchars($settingsMap['instagram_url'] ?? '#') ?>" placeholder="https://instagram.com/...">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="linkedin_url">LinkedIn URL</label>
                                <input type="text" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars($settingsMap['linkedin_url'] ?? '#') ?>" placeholder="https://linkedin.com/...">
                            </div>
                            <div class="form-group">
                                <label for="tiktok_url">TikTok URL</label>
                                <input type="text" id="tiktok_url" name="tiktok_url" value="<?= htmlspecialchars($settingsMap['tiktok_url'] ?? '#') ?>" placeholder="https://tiktok.com/...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="youtube_url">YouTube URL</label>
                            <input type="text" id="youtube_url" name="youtube_url" value="<?= htmlspecialchars($settingsMap['youtube_url'] ?? '#') ?>" placeholder="https://youtube.com/...">
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Settings</button>
                    </form>
                </div>
            </div>

            <h3 style="font-size:14px;font-weight:600;margin:24px 0 16px;padding-bottom:8px;border-bottom:2px solid var(--admin-primary);display:inline-block;">Email / Newsletter Settings</h3>
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="save_settings" value="1">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="mail_from_email">From Email</label>
                                <input type="email" id="mail_from_email" name="mail_from_email" value="<?= htmlspecialchars($settingsMap['mail_from_email'] ?? '') ?>" placeholder="noreply@kimnestcontainers.co.ke">
                            </div>
                            <div class="form-group">
                                <label for="mail_from_name">From Name</label>
                                <input type="text" id="mail_from_name" name="mail_from_name" value="<?= htmlspecialchars($settingsMap['mail_from_name'] ?? '') ?>" placeholder="Kimnest Containers">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Email Settings</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.querySelectorAll('.pickr').forEach(function(el) {
        var fieldName = el.dataset.field;
        var defaultColor = el.dataset.default;
        var textInput = document.getElementById(fieldName);

        var pickr = Pickr.create({
            el: el,
            theme: 'classic',
            default: textInput.value || defaultColor,
            components: {
                preview: true,
                opacity: false,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: false,
                    hsla: false,
                    hsva: false,
                    cmyk: false,
                    input: true,
                    save: true,
                    clear: false,
                    cancel: false
                }
            }
        });

        pickr.on('save', function(color) {
            if (color) {
                var hex = color.toHEXA().toString();
                textInput.value = hex;
                pickr.setColor(hex);
            }
            pickr.hide();
        });

        pickr.on('change', function(color) {
            if (color) {
                textInput.value = color.toHEXA().toString();
            }
        });

        textInput.addEventListener('input', function() {
            pickr.setColor(this.value);
        });
    });

    function uploadSettingImage(input, fieldId, previewId) {
        if (!input.files || !input.files[0]) return;
        var file = input.files[0];
        var formData = new FormData();
        formData.append('files[]', file);
        formData.append('action', 'upload');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= BASE_URL ?>/api/media_handler.php?action=upload', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var resp = JSON.parse(xhr.responseText);
                if (resp.success && resp.uploaded && resp.uploaded.length > 0) {
                    var path = 'uploads/' + resp.uploaded[0].filename;
                    document.getElementById(fieldId).value = path;
                    document.getElementById(previewId).innerHTML = '<img src="<?= BASE_URL ?>/' + path + '" style="max-width:100%;max-height:100%;object-fit:contain;">';
                } else {
                    alert('Upload failed: ' + (resp.message || 'Unknown error'));
                }
            }
        };
        xhr.send(formData);
    }
    </script>
</body>
</html>
