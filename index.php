<?php
$pageTitle = "Home: Tourism & Heritage";

// Helper function to load uploaded images for the homepage
function getUploadedHomepageImages() {
    $uploadsDir = __DIR__ . '/uploads/images';
    $metadataFile = __DIR__ . '/uploads/media_metadata.json';
    $items = [];
    
    if (file_exists($metadataFile)) {
        $meta = json_decode(file_get_contents($metadataFile), true) ?? [];
    } else {
        $meta = [];
    }
    
    if (file_exists($uploadsDir)) {
        $files = scandir($uploadsDir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || str_starts_with($file, '.')) continue;
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) continue;
            
            $relPath = 'uploads/images/' . $file;
            $title = $meta[$relPath]['title'] ?? $file;
            $description = $meta[$relPath]['description'] ?? 'User submitted Isabela destination photo.';
            $category = $meta[$relPath]['category'] ?? 'User Uploaded Spot';
            
            $items[] = [
                'path' => $relPath,
                'title' => $title,
                'description' => $description,
                'category' => ucfirst($category),
                'mtime' => filemtime($uploadsDir . '/' . $file)
            ];
        }
    }
    
    usort($items, function($a, $b) {
        return $b['mtime'] <=> $a['mtime'];
    });
    
    return $items;
}

$uploadedImages = getUploadedHomepageImages();
include 'includes/header.php';
?>

<!-- Background Looped Audio: audio/isabela-environmental-nature.mp3 -->
<audio id="bgAudio" autoplay loop preload="auto">
    <source src="audio/isabela-environmental-nature.mp3" type="audio/mpeg">
    <source src="audio/isabela-environmental-nature.wav" type="audio/wav">
    Your browser does not support HTML5 audio playback.
</audio>

<!-- Section III.A & Section IV: Responsive Media Element (<picture> Tag) -->
<section class="hero-section" aria-label="Featured Isabela Destination Banner">
    <picture class="hero-picture">
        <source media="(min-width: 1025px)" srcset="images/ilagan-sanctuary.png">
        <source media="(min-width: 601px)" srcset="images/ilagan-sanctuary.png">
        <img src="images/ilagan-sanctuary.png" alt="Panoramic view of Isabela eco-tourism sanctuary and natural landscape" class="hero-img lightbox-trigger">
    </picture>
    
    <div class="hero-overlay">
        <span class="hero-tag">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            Queen Province of the North
        </span>
        <h1 class="hero-title">Experience the Beauty of Isabela</h1>
        <p class="hero-desc">Explore majestic waterfalls, historical sanctuaries, and eco-adventure trails through immersive media assets and guided audio tours.</p>
        <div class="btn-group">
            <a href="about.php" class="btn btn-primary">
                <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Explore Destinations
            </a>
            <a href="gallery.php" class="btn btn-secondary">
                <svg class="icon-svg" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                Media Gallery
            </a>
            <button id="toggleBgAudioBtn" class="btn btn-secondary" style="background: rgba(255,255,255,0.95); color: var(--color-text-primary);">
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
                <span class="audio-btn-label">Ambient Music: Playing</span>
            </button>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section" style="margin-bottom: 3.5rem;">
    <div class="section-header">
        <h2>Welcome to Isabela's Eco-Tourism Portal</h2>
        <p>Discover top destination spots, cultural heritage landmarks, and interactive media guides designed for all travelers.</p>
    </div>
    
    <!-- Section III.B: Image Integration Cards -->
    <div class="cards-grid">
        
        <article class="card">
            <div class="card-media">
                <img src="images/ilagan-sanctuary.png" alt="Ilagan Sanctuary eco-park featuring lush greenery and recreation area" class="lightbox-trigger" loading="lazy">
            </div>
            <div class="card-body">
                <span class="badge badge-image" style="margin-bottom: 0.5rem; width: fit-content;">
                    <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                    Eco Park
                </span>
                <h3 class="card-title">Ilagan Sanctuary & Japanese Tunnel</h3>
                <p class="card-text">A premier eco-tourism destination in Santa Victoria Caves featuring zip lines, wildlife, and historical Japanese tunnels.</p>
            </div>
        </article>
        
        <article class="card">
            <div class="card-media">
                <img src="images/dibulo-falls.png" alt="Dibulo Falls in Dinapigue Isabela with cascading natural waters" class="lightbox-trigger" loading="lazy">
            </div>
            <div class="card-body">
                <span class="badge badge-image" style="margin-bottom: 0.5rem; width: fit-content;">
                    <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>
                    Waterfall
                </span>
                <h3 class="card-title">Dibulo Falls: Dinapigue</h3>
                <p class="card-text">One of the highest cascading waterfalls in the region surrounded by pristine rainforests and rich biodiversity.</p>
            </div>
        </article>

        <article class="card">
            <div class="card-media">
                <img src="images/abuan-river.png" alt="Abuan River eco-adventure trail with clear flowing river water" class="lightbox-trigger" loading="lazy">
            </div>
            <div class="card-body">
                <span class="badge badge-image" style="margin-bottom: 0.5rem; width: fit-content;">
                    <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    Adventure
                </span>
                <h3 class="card-title">Abuan River Eco-Adventure</h3>
                <p class="card-text">Home to extreme river kayaking, rappelling, and rich aquatic life situated within the Northern Sierra Madre Biosphere.</p>
            </div>
        </article>

        <article class="card">
            <div class="card-media">
                <img src="images/japanese-tunnel.png" alt="Japanese Tunnel historical WWII landmark in Ilagan Sanctuary" class="lightbox-trigger" loading="lazy">
            </div>
            <div class="card-body">
                <span class="badge badge-image" style="margin-bottom: 0.5rem; width: fit-content;">
                    <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    Historical Site
                </span>
                <h3 class="card-title">Japanese Tunnel WWII Heritage</h3>
                <p class="card-text">Preserved World War II subterranean tunnels constructed by Japanese forces located within the Ilagan Eco-Sanctuary complex.</p>
            </div>
        </article>
        
        <!-- Dynamic User-Uploaded Images on Homepage Grid -->
        <?php foreach ($uploadedImages as $upImg): ?>
            <article class="card">
                <div class="card-media">
                    <img src="<?php echo htmlspecialchars($upImg['path']); ?>" alt="Uploaded media: <?php echo htmlspecialchars($upImg['title']); ?>" class="lightbox-trigger" loading="lazy">
                </div>
                <div class="card-body">
                    <span class="badge badge-image" style="margin-bottom: 0.5rem; width: fit-content;">
                        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        <?php echo htmlspecialchars($upImg['category']); ?>
                    </span>
                    <h3 class="card-title"><?php echo htmlspecialchars($upImg['title']); ?></h3>
                    <p class="card-text"><?php echo htmlspecialchars($upImg['description']); ?></p>
                </div>
            </article>
        <?php endforeach; ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
