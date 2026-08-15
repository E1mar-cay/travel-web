<?php
$pageTitle = "Contact & Media Documentation";
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contactSubmit'])) {
    $submitted = true;
}

include 'includes/header.php';
?>

<div class="section-header">
    <span class="badge badge-image" style="margin-bottom: 0.5rem;">
        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        Documentation & Contact
    </span>
    <h1>Contact & Media Format Documentation</h1>
    <p>Submit inquiries regarding Isabela tourism or review the technical media format justification matrix required by IT AppDev 2 Section IX.</p>
</div>

<!-- Section IX: Media Format Documentation Table -->
<section class="media-container" style="margin-bottom: 3.5rem;">
    <h2>Section IX: Media Format Documentation Matrix</h2>
    <p>The following table justifies the technical selection of each media format implemented across our web application:</p>
    
    <div class="table-responsive">
        <table class="doc-table">
            <thead>
                <tr>
                    <th scope="col">Media Type</th>
                    <th scope="col">Format Used</th>
                    <th scope="col">Purpose</th>
                    <th scope="col">Reason for Selection</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Image</strong></td>
                    <td><span class="badge badge-image">PNG / WebP / JPEG</span></td>
                    <td>Responsive hero banners, destination spotlight cards, and gallery previews</td>
                    <td>High compression efficiency, lossless/lossy transparency support, `<picture>` tag viewport switching, and broad browser compatibility.</td>
                </tr>
                <tr>
                    <td><strong>Audio</strong></td>
                    <td><span class="badge badge-audio">MP3 / WAV / OGG</span></td>
                    <td>Guided tour narration, environmental ambient sound, and voice intro previews</td>
                    <td>Native HTML5 `<audio>` playback support across modern browsers, minimal file footprint, and clean acoustic clarity.</td>
                </tr>
                <tr>
                    <td><strong>Video</strong></td>
                    <td><span class="badge badge-video">MP4 (H.264 / AAC) / WebM</span></td>
                    <td>Promotional eco-tourism showcase and destination features</td>
                    <td>Universal hardware acceleration across desktop and mobile devices; supports VTT closed captioning and transcripts.</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
    
    <div class="card" style="padding: 2rem;">
        <h3>Get in Touch with Isabela Tourism</h3>
        <?php if ($submitted): ?>
            <div class="alert alert-success" style="margin-top: 1rem;">
                Thank you for your inquiry! Your message has been sent successfully.
            </div>
        <?php endif; ?>
        
        <form action="contact.php" method="POST" style="margin-top: 1rem;">
            <input type="hidden" name="contactSubmit" value="1">
            <div class="form-group">
                <label for="senderName" class="form-label">Your Full Name:</label>
                <input type="text" id="senderName" name="senderName" class="form-control" placeholder="Jane Doe" required>
            </div>
            
            <div class="form-group">
                <label for="senderEmail" class="form-label">Email Address:</label>
                <input type="email" id="senderEmail" name="senderEmail" class="form-control" placeholder="jane@example.com" required>
            </div>

            <div class="form-group">
                <label for="messageBody" class="form-label">Inquiry / Message:</label>
                <textarea id="messageBody" name="messageBody" class="form-control" rows="4" placeholder="Ask about tour packages or local heritage spots..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <svg class="icon-svg" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                Send Inquiry
            </button>
        </form>
    </div>

    <div class="card" style="padding: 2rem;">
        <h3>Project Credits & Syllabus Information</h3>
        <ul style="color: var(--color-text-muted); list-style: none; line-height: 2; font-size: var(--text-sm);">
            <li><strong>Course:</strong> IT AppDev 2: Web Applications</li>
            <li><strong>Activity:</strong> Media Content Integration in Web Applications</li>
            <li><strong>CLO Alignment:</strong> CLO1 (Weeks 2 to 3)</li>
            <li><strong>Website Concept:</strong> Travel & Tourism Website</li>
            <li><strong>Target Location:</strong> Province of Isabela, Philippines</li>
            <li><strong>Frameworks & Tech:</strong> PHP 8.2, HTML5 Semantic Elements, Modern CSS3, Pure JS</li>
            <li><strong>Accessibility Standard:</strong> WCAG 2.1 Level AA Compliant</li>
        </ul>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
