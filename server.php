<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * Router script for the PHP built-in web server.
 * Allows clean URLs across all pages and subpages.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? ''
);

// If the file exists directly in public directory, return false to let PHP's web server serve it
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
