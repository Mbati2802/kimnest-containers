<?php
require_once __DIR__ . '/functions.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
if (isset($_GET['slug'])) $currentPage = 'blog';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kimnest Containers - Kenya's leading container sales, fabrication, and modification company. Container homes, offices, shops, and custom solutions.">
    <meta name="keywords" content="Shipping Containers Kenya, Container Fabrication, Container Homes, Office Containers, Portable Cabins">
    <title><?= getPageTitle($pageTitle ?? '') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <a href="<?= BASE_URL ?>/" class="logo">
                <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Kimnest Containers" onerror="this.style.display='none'">
                <span class="logo-text">Kim<span>nest</span></span>
            </a>
            
            <nav class="nav" id="nav">
                <a href="<?= BASE_URL ?>/" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">Home</a>
                <a href="<?= BASE_URL ?>/about" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>">About</a>
                <a href="<?= BASE_URL ?>/services" class="nav-link <?= $currentPage === 'services' ? 'active' : '' ?>">Services</a>
                <a href="<?= BASE_URL ?>/products" class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>">Products</a>
                <a href="<?= BASE_URL ?>/projects" class="nav-link <?= $currentPage === 'projects' ? 'active' : '' ?>">Projects</a>
                <a href="<?= BASE_URL ?>/blog" class="nav-link <?= $currentPage === 'blog' ? 'active' : '' ?>">Blog</a>
                <a href="<?= BASE_URL ?>/faq" class="nav-link <?= $currentPage === 'faq' ? 'active' : '' ?>">FAQ</a>
                <a href="<?= BASE_URL ?>/contact" class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>">Contact</a>
            </nav>
            
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/request-quote" class="btn btn-primary">Get a Quote</a>
                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <main class="main">
