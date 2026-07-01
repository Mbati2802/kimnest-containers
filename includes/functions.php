<?php
require_once __DIR__ . '/db.php';

// Flash messages
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Format date
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

// Generate slug
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text);
}

// Get recent blog posts
function getRecentPosts($limit = 3) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// Get featured projects
function getFeaturedProjects($limit = 3) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE featured = 1 AND status = 'completed' ORDER BY completed_date DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// Get all projects by category
function getProjectsByCategory($category = null, $limit = null) {
    global $pdo;
    $sql = "SELECT * FROM projects WHERE status = 'completed'";
    $params = [];
    
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY completed_date DESC";
    
    if ($limit) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get project by slug
function getProjectBySlug($slug) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Get project images
function getProjectImages($projectId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order");
    $stmt->execute([$projectId]);
    return $stmt->fetchAll();
}

// Get blog post by slug
function getPostBySlug($slug) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ? AND status = 'published'");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Handle file upload
function uploadFile($file, $directory = 'uploads') {
    $uploadDir = __DIR__ . '/../' . $directory . '/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $directory . '/' . $filename;
    }
    
    return false;
}

// Validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Get page title
function getPageTitle($page = '') {
    $siteName = 'KimNest Containers (K)';
    if ($page) {
        return $page . ' | ' . $siteName;
    }
    return $siteName . ' | We Weave Your Property Dream into Reality';
}

// Truncate text
function truncate($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

// Get content from site_content table (respects visibility)
function getContent($page, $section, $key, $default = '') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT content_value, content_type, is_visible FROM site_content WHERE page_slug = ? AND section_key = ? AND content_key = ?");
    $stmt->execute([$page, $section, $key]);
    $result = $stmt->fetch();
    if (!$result) return $default;
    if (isset($result['is_visible']) && $result['is_visible'] == 0) return '';
    $value = $result['content_value'];
    $type = $result['content_type'] ?? 'text';
    if ($type === 'textarea') {
        $value = strip_tags($value);
        $value = trim($value);
    }
    return $value;
}

// Get raw content ignoring visibility (for admin)
function getContentRaw($page, $section, $key, $default = '') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT content_value FROM site_content WHERE page_slug = ? AND section_key = ? AND content_key = ?");
    $stmt->execute([$page, $section, $key]);
    $result = $stmt->fetch();
    return $result ? $result['content_value'] : $default;
}

// Get setting from site_settings table
function getSetting($key, $default = '') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : $default;
}

// Get all products
function getProducts($category = null) {
    global $pdo;
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY sort_order ASC, id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get product by slug
function getProductBySlug($slug) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Get product categories
function getProductCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get product images
function getProductImages($productId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order ASC, id ASC");
    $stmt->execute([$productId]);
    return $stmt->fetchAll();
}

function getFAQs($category = null) {
    global $pdo;
    if ($category) {
        $stmt = $pdo->prepare("SELECT * FROM faqs WHERE category = ? ORDER BY sort_order ASC, id ASC");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC, id ASC");
    }
    return $stmt->fetchAll();
}

function getFAQCategories() {
    global $pdo;
    return $pdo->query("SELECT DISTINCT category FROM faqs ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
}

function sendMail($to, $subject, $body, $fromEmail = null, $fromName = null) {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('mail_from_email','mail_from_name','site_name')");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (Exception $e) { $settings = []; }
    $fromEmail = $fromEmail ?: ($settings['mail_from_email'] ?? 'noreply@kimnestcontainers.co.ke');
    $fromName  = $fromName  ?: ($settings['mail_from_name'] ?? ($settings['site_name'] ?? 'Kimnest Containers'));
    $headers   = "From: $fromName <$fromEmail>\r\n";
    $headers  .= "Reply-To: $fromEmail\r\n";
    $headers  .= "MIME-Version: 1.0\r\n";
    $headers  .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $body, $headers);
}
