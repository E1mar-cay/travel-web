<?php
$pageTitle = "About: Discover Isabela";
include 'includes/header.php';
?>

<div class="section-header">
    <span class="badge badge-video" style="margin-bottom: 0.5rem;">
        <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
        Video Presentation & Overview
    </span>
    <h1>About Isabela Tourism & Heritage Portal</h1>
    <p>Learn about our province's heritage, watch our promotional travel video, and discover how modern web media formats enhance the travel experience.</p>
</div>

<!-- Featured Video Showcase Section -->
<section class="media-container" style="margin-bottom: 3.5rem;">
    <h2>Featured Travel Video Presentation</h2>
    <p>Watch our official promotional video showcasing the breathtaking landscapes, eco-tourism spots, and cultural heritage of Isabela Province.</p>
    
    <div class="video-box" style="margin: 1.5rem 0;">
        <video controls width="100%" poster="images/ilagan-sanctuary.png" preload="metadata">
            <source src="videos/isabela video.mp4" type="video/mp4">
            <track src="videos/subtitles.vtt" kind="subtitles" srclang="en" label="English Captions">
            Your browser does not support HTML5 video playback.
        </video>
    </div>

    <div class="transcript-box">
        <button class="transcript-toggle" aria-expanded="false">
            <span>Video Transcript & Narrative Summary</span>
            <span style="display: inline-flex; align-items: center; gap: 0.3rem;">
                <span class="transcript-status-label">Show Transcript</span>
                <svg class="icon-svg transcript-icon" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </span>
        </button>
        <div class="transcript-content">
            <p><strong>Official Travel Feature: Isabela Province</strong></p>
            <p>The video opens with panoramic views of Isabela's lush forests, cascading waterfalls, historical landmarks, and rich cultural festivities across Cagayan Valley.</p>
        </div>
    </div>
</section>

<!-- Featured Destination Cards -->
<section class="media-container" style="margin-bottom: 3.5rem;">
    <h2>Featured Destination: Ilagan Eco-Sanctuary & Japanese Tunnel</h2>
    <p>Discover one of Isabela's crowning glory attractions featuring lush canopy forests, pristine caves, zip lines, and historical World War II Japanese tunnels.</p>
    
    <div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); margin: 1.5rem 0;">
        <div class="card">
            <div class="card-media">
                <img src="images/ilagan-sanctuary.png" alt="Ilagan Sanctuary Eco-Park" class="lightbox-trigger">
            </div>
            <div class="card-body">
                <h3>Ilagan Eco-Park</h3>
                <p class="card-text">Lush forest reserve featuring wildlife, swimming pools, cable cars, and limestone cave formations.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-media">
                <img src="images/japanese-tunnel.png" alt="Japanese Tunnel WWII Heritage" class="lightbox-trigger">
            </div>
            <div class="card-body">
                <h3>WWII Japanese Tunnel</h3>
                <p class="card-text">Preserved subterranean military structure constructed during World War II.</p>
            </div>
        </div>
    </div>
</section>

<!-- Purpose & Target Audience Cards -->
<section style="margin-bottom: 3.5rem;">
    <h2>Website Purpose & Target Audience</h2>
    <div class="cards-grid">
        <article class="card">
            <div class="card-body">
                <h3 class="card-title">Target Travelers & Heritage Enthusiasts</h3>
                <p class="card-text">Designed for local and international tourists, students, and culture enthusiasts looking for authenticated travel itineraries, media previews, and downloadable destination assets.</p>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="card-title">Responsive & Adaptive Architecture</h3>
                <p class="card-text">Built with mobile-first media queries, `<picture>` responsive image switching, compressed audio streams, and flexible video containers across all screen sizes.</p>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="card-title">Inclusive Web Accessibility</h3>
                <p class="card-text">Ensures compliance with accessibility standards through descriptive alt attributes, screen-reader labels, high-contrast typography, and full keyboard navigation support.</p>
            </div>
        </article>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
