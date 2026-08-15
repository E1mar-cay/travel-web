<?php
function isActive($pageName) {
    $current = basename($_SERVER['PHP_SELF']);
    return ($current === $pageName) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discover Isabela: Official Tourism and Cultural Heritage Portal featuring responsive media, audio tours, video presentations, and interactive gallery.">
    <meta name="author" content="IT AppDev 2 Student Project">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Discover Isabela' : 'Discover Isabela | Tourism & Heritage Website'; ?></title>
    
    <!-- Google Fonts: Outfit (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Core Design Tokens & Anti-Generic Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Site Header Navigation -->
    <header class="site-header">
        <div class="container">
            <div class="nav-wrapper">
                <a href="index.php" class="logo-brand" aria-label="Discover Isabela Home">
                    <img src="images/logo.png" alt="Province of Isabela Official Seal" style="height: 42px; width: auto; object-fit: contain;">
                    <span>Discover Isabela</span>
                </a>
                
                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Navigation Menu" aria-expanded="false">
                    <svg class="icon-svg" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                
                <nav>
                    <ul class="nav-menu" id="navMenu">
                        <li><a href="index.php" class="nav-link <?php echo isActive('index.php'); ?>">Home</a></li>
                        <li><a href="about.php" class="nav-link <?php echo isActive('about.php'); ?>">About</a></li>
                        <li><a href="gallery.php" class="nav-link <?php echo isActive('gallery.php'); ?>">Media Gallery</a></li>
                        <li><a href="upload.php" class="nav-link <?php echo isActive('upload.php'); ?>">Upload Media</a></li>
                        <li><a href="contact.php" class="nav-link <?php echo isActive('contact.php'); ?>">Contact & Docs</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main id="main-content">
        <div class="container">
