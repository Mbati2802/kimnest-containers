<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$uploadDir = __DIR__ . '/../uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'upload':
        handleUpload($uploadDir);
        break;
    case 'delete':
        handleDelete($uploadDir);
        break;
    case 'list':
        handleList($uploadDir);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function handleUpload($uploadDir) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        return;
    }

    if (!isset($_FILES['files'])) {
        echo json_encode(['success' => false, 'message' => 'No files provided']);
        return;
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    $uploaded = [];
    $errors = [];

    $files = $_FILES['files'];
    $fileCount = is_array($files['name']) ? count($files['name']) : 1;

    for ($i = 0; $i < $fileCount; $i++) {
        $name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
        $tmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
        $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];
        $size = is_array($files['size']) ? $files['size'][$i] : $files['size'];
        $type = is_array($files['type']) ? $files['type'][$i] : $files['type'];

        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading $name";
            continue;
        }

        if (!in_array($type, $allowedTypes)) {
            $errors[] = "File type not allowed: $name";
            continue;
        }

        if ($size > MAX_FILE_SIZE) {
            $errors[] = "File too large: $name";
            continue;
        }

        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $filename = uniqid('img_', true) . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $filepath)) {
            $xamppUpload = 'C:/xampp/htdocs/kimnest/uploads/' . $filename;
            if (!is_dir('C:/xampp/htdocs/kimnest/uploads/')) {
                mkdir('C:/xampp/htdocs/kimnest/uploads/', 0755, true);
            }
            copy($filepath, $xamppUpload);

            $uploaded[] = [
                'filename' => $filename,
                'original_name' => $name,
                'url' => BASE_URL . '/uploads/' . $filename,
                'size' => $size,
                'type' => $type
            ];
        } else {
            $errors[] = "Failed to save $name";
        }
    }

    echo json_encode([
        'success' => count($uploaded) > 0,
        'uploaded' => $uploaded,
        'errors' => $errors,
        'message' => count($uploaded) . ' file(s) uploaded' . (count($errors) > 0 ? ', ' . count($errors) . ' failed' : '')
    ]);
}

function handleDelete($uploadDir) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        return;
    }

    $filename = $_POST['filename'] ?? '';
    if (empty($filename)) {
        echo json_encode(['success' => false, 'message' => 'No filename provided']);
        return;
    }

    // Sanitize filename to prevent directory traversal
    $filename = basename($filename);
    $filepath = $uploadDir . $filename;

    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            $xamppFile = 'C:/xampp/htdocs/kimnest/uploads/' . $filename;
            if (file_exists($xamppFile)) unlink($xamppFile);
            echo json_encode(['success' => true, 'message' => 'File deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete file']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File not found']);
    }
}

function handleList($uploadDir) {
    $images = [];
    $seen = [];
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    $dirs = [$uploadDir, 'C:/xampp/htdocs/kimnest/uploads/'];
    foreach ($dirs as $dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                if (isset($seen[$file])) continue;
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $allowedExts)) {
                    $filepath = $dir . $file;
                    $images[] = [
                        'filename' => $file,
                        'url' => BASE_URL . '/uploads/' . $file,
                        'size' => filesize($filepath),
                        'modified' => filemtime($filepath)
                    ];
                    $seen[$file] = true;
                }
            }
        }
    }

    // Sort by modified time, newest first
    usort($images, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });

    echo json_encode(['success' => true, 'images' => $images]);
}
