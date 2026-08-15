<?php
$pageTitle = "Upload Media: Discover Isabela";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['mediaFile'])) {
    $file = $_FILES['mediaFile'];
    $title = trim($_POST['mediaTitle'] ?? '');
    $description = trim($_POST['mediaDescription'] ?? '');
    $category = $_POST['mediaCategory'] ?? 'destination';
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = "File upload failed. Please try again. (Error code: " . $file['error'] . ")";
        $messageType = "error";
    } else {
        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileTmpPath = $file['tmp_path'] ?? $file['tmp_name'];
        $maxSizeBytes = 50 * 1024 * 1024; // 50 MB limit to accommodate videos
        
        $allowedTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm'
        ];
        
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if ($fileSize > $maxSizeBytes) {
            $message = "Validation Error: File size exceeds the maximum limit of 50MB.";
            $messageType = "error";
        } elseif (!array_key_exists($fileExt, $allowedTypes)) {
            $message = "Validation Error: Invalid file format (." . htmlspecialchars($fileExt) . "). Allowed formats: JPG, PNG, WebP, SVG, MP3, WAV, OGG, MP4, WebM.";
            $messageType = "error";
        } else {
            $subFolder = 'images';
            if (in_array($fileExt, ['mp3', 'wav', 'ogg'])) {
                $subFolder = 'audio';
            } elseif (in_array($fileExt, ['mp4', 'webm'])) {
                $subFolder = 'videos';
            }
            
            $targetDir = __DIR__ . '/uploads/' . $subFolder . '/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $rawName = pathinfo($fileName, PATHINFO_FILENAME);
            $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '-', strtolower($rawName));
            $newFileName = 'isabela-' . $cleanName . '-' . time() . '.' . $fileExt;
            $destination = $targetDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $destination)) {
                $relPath = 'uploads/' . $subFolder . '/' . $newFileName;
                $metadata = getMetadata($metadataFile);
                $metadata[$relPath] = [
                    'title' => !empty($title) ? $title : $newFileName,
                    'description' => $description,
                    'category' => $category,
                    'uploaded_at' => date('Y-m-d H:i:s')
                ];
                saveMetadata($metadataFile, $metadata);

                $message = "Success! Media asset '" . htmlspecialchars(!empty($title) ? $title : $newFileName) . "' uploaded successfully. It is now featured in the Media Gallery!";
                $messageType = "success";
            } else {
                $message = "Server Error: Could not save file to target destination folder.";
                $messageType = "error";
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="section-header">
    <span class="badge badge-image" style="margin-bottom: 0.5rem;">
        <svg class="icon-svg" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
        File Upload Portal
    </span>
    <h1>Upload Destination Media Assets</h1>
    <p>Submit your Isabela travel photos, audio narrations, or video features. Uploaded assets automatically appear in the Media Gallery.</p>
</div>

<div class="upload-card">
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="upload.php" method="POST" enctype="multipart/form-data" id="uploadForm">
        
        <div class="form-group">
            <label for="mediaTitle" class="form-label">Media Title:</label>
            <input type="text" id="mediaTitle" name="mediaTitle" class="form-control" placeholder="e.g. Tumauini Church Sunset View" required>
        </div>

        <div class="form-group">
            <label for="mediaDescription" class="form-label">Description:</label>
            <textarea id="mediaDescription" name="mediaDescription" class="form-control" rows="3" placeholder="Write a short description of the destination spot..." required></textarea>
        </div>

        <div class="form-group">
            <label for="mediaCategory" class="form-label">Media Category:</label>
            <select id="mediaCategory" name="mediaCategory" class="form-control">
                <option value="destination">Destination Spot</option>
                <option value="culture">Cultural Heritage</option>
                <option value="festival">Festival Event</option>
                <option value="nature">Eco-Tourism & Nature</option>
            </select>
        </div>

        <div class="form-group">
            <label for="mediaFile" class="form-label">Select Media File (Max 50MB):</label>
            <div class="drop-zone" id="dropZone">
                <div style="font-size: 2.2rem; color: var(--color-brand-light); margin-bottom: 0.5rem;">
                    <svg class="icon-svg" style="width: 2.5rem; height: 2.5rem;" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
                </div>
                <p style="color: var(--color-text-primary); font-weight: 600; margin-bottom: 0.25rem;">Drag and drop your file here or click to browse</p>
                <p style="font-size: var(--text-xs); color: var(--color-text-muted);">Supports: WebP, JPEG, PNG, SVG, MP3, WAV, OGG, MP4, WebM</p>
                <input type="file" id="mediaFile" name="mediaFile" accept="image/*,audio/*,video/*" style="display: none;" required>
            </div>
            <div id="filePreview"></div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Upload Media File
        </button>

    </form>
</div>

<section class="media-container" style="max-width: 620px; margin: 0 auto 3rem auto; padding: 1.5rem;">
    <h3>Upload Validation Rules</h3>
    <ul style="color: var(--color-text-muted); padding-left: 1.25rem; line-height: 1.8; font-size: var(--text-sm);">
        <li><strong>Allowed Image Formats:</strong> JPEG, PNG, WebP, SVG</li>
        <li><strong>Allowed Audio Formats:</strong> MP3, WAV, OGG</li>
        <li><strong>Allowed Video Formats:</strong> MP4, WebM</li>
        <li><strong>Maximum File Size:</strong> 50 MegaBytes (MB)</li>
    </ul>
</section>

<?php include 'includes/footer.php'; ?>
