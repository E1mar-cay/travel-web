<?php
$pageTitle = "Media Gallery: Discover Isabela";
$message = "";
$messageType = "";

$metadataFile = __DIR__ . '/uploads/media_metadata.json';

function getMetadata($filePath) {
    if (file_exists($filePath)) {
        $json = file_get_contents($filePath);
        return json_decode($json, true) ?? [];
    }
    return [];
}

function saveMetadata($filePath, $data) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

$metadata = getMetadata($metadataFile);

// UPDATE ACTION: Edit Title, Description & Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $filePath = $_POST['filePath'] ?? '';
    $newTitle = trim($_POST['mediaTitle'] ?? '');
    $newDesc = trim($_POST['mediaDescription'] ?? '');
    $newCategory = $_POST['mediaCategory'] ?? 'destination';
    
    if (!empty($filePath) && file_exists(__DIR__ . '/' . $filePath)) {
        $metadata[$filePath] = [
            'title' => !empty($newTitle) ? $newTitle : basename($filePath),
            'description' => $newDesc,
            'category' => $newCategory,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        saveMetadata($metadataFile, $metadata);
        
        $message = "Media details for '" . htmlspecialchars(basename($filePath)) . "' updated successfully!";
        $messageType = "success";
    } else {
        $message = "Error: Invalid target file for update.";
        $messageType = "error";
    }
}

// DELETE ACTION: Remove File & Metadata
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $fileToDelete = $_POST['filePath'] ?? '';
    $realUploadsDir = realpath(__DIR__ . '/uploads');
    $realTargetFile = realpath(__DIR__ . '/' . $fileToDelete);
    
    if ($realTargetFile && str_starts_with($realTargetFile, $realUploadsDir) && file_exists($realTargetFile)) {
        if (unlink($realTargetFile)) {
            if (isset($metadata[$fileToDelete])) {
                unset($metadata[$fileToDelete]);
                saveMetadata($metadataFile, $metadata);
            }
            $message = "File '" . htmlspecialchars(basename($fileToDelete)) . "' deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Failed to delete file from disk.";
            $messageType = "error";
        }
    } else {
        $message = "Error: Invalid file path or deletion restriction.";
        $messageType = "error";
    }
}

// READ ACTION: Prepare All Media Items (Images, Audio, Videos)
function getMediaItems($metadata) {
    $items = [];
    
    $sources = [
        'built-in' => [
            'images' => __DIR__ . '/images',
            'audio' => __DIR__ . '/audio',
            'videos' => __DIR__ . '/videos'
        ],
        'uploads' => [
            'images' => __DIR__ . '/uploads/images',
            'audio' => __DIR__ . '/uploads/audio',
            'videos' => __DIR__ . '/uploads/videos'
        ]
    ];
    
    foreach ($sources as $sourceType => $types) {
        foreach ($types as $mediaType => $dirPath) {
            if (file_exists($dirPath)) {
                $files = scandir($dirPath);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..' || str_starts_with($file, '.')) continue;
                    
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($ext, ['vtt'])) continue;
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'mp3', 'wav', 'ogg', 'mp4', 'webm'])) continue;
                    
                    $fullPath = $dirPath . '/' . $file;
                    $relPath = ($sourceType === 'uploads') ? 'uploads/' . $mediaType . '/' . $file : $mediaType . '/' . $file;
                    $sizeBytes = filesize($fullPath);
                    $sizeFormatted = round($sizeBytes / 1024, 1) . ' KB';
                    if ($sizeBytes > 1024 * 1024) {
                        $sizeFormatted = round($sizeBytes / (1024 * 1024), 2) . ' MB';
                    }
                    
                    $meta = $metadata[$relPath] ?? [];
                    $displayTitle = !empty($meta['title']) ? $meta['title'] : $file;
                    $displayDesc = !empty($meta['description']) ? $meta['description'] : '';
                    $categoryTag = !empty($meta['category']) ? $meta['category'] : 'destination';

                    $items[] = [
                        'filename' => $file,
                        'title' => $displayTitle,
                        'description' => $displayDesc,
                        'category' => $categoryTag,
                        'path' => $relPath,
                        'type' => $mediaType,
                        'ext' => strtoupper($ext),
                        'size' => $sizeFormatted,
                        'isDeletable' => ($sourceType === 'uploads'),
                        'mtime' => filemtime($fullPath)
                    ];
                }
            }
        }
    }
    
    usort($items, function($a, $b) {
        return $b['mtime'] <=> $a['mtime'];
    });
    
    return $items;
}

$mediaItems = getMediaItems($metadata);
include 'includes/header.php';
?>

<div class="section-header">
    <span class="badge badge-image" style="margin-bottom: 0.5rem;">
        <svg class="icon-svg" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        Media Management
    </span>
    <h1>Interactive Media Gallery</h1>
    <p>View, filter, play, edit, and delete travel images, audio tracks, and videos across Isabela Province.</p>
</div>

<?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>" role="alert" style="max-width: 800px; margin: 0 auto 2rem auto;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Category READ Filter Buttons -->
<div class="gallery-filters" role="group" aria-label="Filter media gallery by type">
    <button class="filter-btn active" data-filter="all">All Assets (<?php echo count($mediaItems); ?>)</button>
    <button class="filter-btn" data-filter="images">Images</button>
    <button class="filter-btn" data-filter="audio">Audio Tracks</button>
    <button class="filter-btn" data-filter="videos">Videos</button>
</div>

<!-- Media Cards Grid -->
<div class="cards-grid" id="galleryGrid">
    <?php foreach ($mediaItems as $item): ?>
        <article class="card gallery-item" data-category="<?php echo $item['type']; ?>">
            
            <div class="card-media" style="padding: 0.85rem; background: var(--color-overlay); min-height: 190px; display: flex; align-items: center; justify-content: center;">
                <?php if ($item['type'] === 'images'): ?>
                    <img src="<?php echo htmlspecialchars($item['path']); ?>" alt="Media asset: <?php echo htmlspecialchars($item['title']); ?>" class="lightbox-trigger" style="max-height: 170px; width: 100%; object-fit: cover;">
                <?php elseif ($item['type'] === 'audio'): ?>
                    <div style="width: 100%; text-align: center;">
                        <div style="color: var(--color-accent-gold); margin-bottom: 0.5rem;">
                            <svg class="icon-svg" style="width: 2.5rem; height: 2.5rem;" viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
                        </div>
                        <audio controls style="width: 100%;">
                            <source src="<?php echo htmlspecialchars($item['path']); ?>">
                            Your browser does not support audio element.
                        </audio>
                    </div>
                <?php elseif ($item['type'] === 'videos'): ?>
                    <video controls style="width: 100%; max-height: 170px;">
                        <source src="<?php echo htmlspecialchars($item['path']); ?>" type="video/mp4">
                        Your browser does not support video element.
                    </video>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span class="badge badge-<?php echo rtrim($item['type'], 's'); ?>">
                        <?php echo strtoupper($item['type']); ?> (<?php echo $item['ext']; ?>)
                    </span>
                    <span style="font-size: var(--text-xs); color: var(--color-text-muted);"><?php echo $item['size']; ?></span>
                </div>
                
                <h3 class="card-title" style="font-size: var(--text-base);">
                    <?php echo htmlspecialchars($item['title']); ?>
                </h3>

                <?php if (!empty($item['description'])): ?>
                    <p class="card-text" style="font-size: var(--text-xs); margin-bottom: 0.5rem;">
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>
                <?php endif; ?>
                
                <p style="font-size: var(--text-xs); color: var(--color-text-dim); margin-bottom: 0.85rem;">
                    File: <?php echo htmlspecialchars($item['filename']); ?>
                </p>
                
                <!-- UPDATE & DELETE Actions for Uploaded Media -->
                <div style="margin-top: auto; padding-top: 0.85rem; border-top: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 0.5rem;">
                    
                    <?php if ($item['isDeletable']): ?>
                        <details style="font-size: var(--text-xs);">
                            <summary style="cursor: pointer; color: var(--color-accent-sky); font-weight: 600;">Edit Details</summary>
                            <form action="gallery.php" method="POST" style="margin-top: 0.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="filePath" value="<?php echo htmlspecialchars($item['path']); ?>">
                                <input type="text" name="mediaTitle" class="form-control" style="font-size: var(--text-xs); padding: 0.4rem;" value="<?php echo htmlspecialchars($item['title']); ?>" placeholder="Title" required>
                                <textarea name="mediaDescription" class="form-control" style="font-size: var(--text-xs); padding: 0.4rem;" rows="2" placeholder="Description..."><?php echo htmlspecialchars($item['description']); ?></textarea>
                                <select name="mediaCategory" class="form-control" style="font-size: var(--text-xs); padding: 0.4rem;">
                                    <option value="destination" <?php echo ($item['category'] === 'destination') ? 'selected' : ''; ?>>Destination</option>
                                    <option value="culture" <?php echo ($item['category'] === 'culture') ? 'selected' : ''; ?>>Culture</option>
                                    <option value="nature" <?php echo ($item['category'] === 'nature') ? 'selected' : ''; ?>>Eco-Nature</option>
                                </select>
                                <button type="submit" class="btn btn-secondary" style="font-size: var(--text-xs); padding: 0.3rem 0.6rem;">Save Changes</button>
                            </form>
                        </details>

                        <form action="gallery.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this media file?');" style="text-align: right;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="filePath" value="<?php echo htmlspecialchars($item['path']); ?>">
                            <button type="submit" class="btn btn-danger">
                                <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                Delete
                            </button>
                        </form>
                    <?php else: ?>
                        <span style="font-size: var(--text-xs); color: var(--color-text-dim);">Built-in Core Asset</span>
                    <?php endif; ?>

                </div>
            </div>

        </article>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
