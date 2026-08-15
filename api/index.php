<?php
// Vercel Serverless Router for PHP Web Application

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Normalize path
$path = rtrim($path, '/');
if (empty($path)) {
    $path = '/index';
}

// Map clean URLs and .php files
$routes = [
    '/' => __DIR__ . '/../index.php',
    '/index' => __DIR__ . '/../index.php',
    '/index.php' => __DIR__ . '/../index.php',
    '/about' => __DIR__ . '/../about.php',
    '/about.php' => __DIR__ . '/../about.php',
    '/gallery' => __DIR__ . '/../gallery.php',
    '/gallery.php' => __DIR__ . '/../gallery.php',
    '/upload' => __DIR__ . '/../upload.php',
    '/upload.php' => __DIR__ . '/../upload.php',
    '/contact' => __DIR__ . '/../contact.php',
    '/contact.php' => __DIR__ . '/../contact.php'
];

if (isset($routes[$path])) {
    require $routes[$path];
    exit;
}

// Check direct target file
$targetFile = __DIR__ . '/..' . $path;
if (file_exists($targetFile) && !is_dir($targetFile)) {
    require $targetFile;
    exit;
}

// Default fallback
require __DIR__ . '/../index.php';
