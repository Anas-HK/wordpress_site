<?php
/**
 * CSS Debug Page
 * Place in WordPress root to check CSS loading
 */

// Load WordPress
require_once('wp-config.php');

echo "<h1>CSS Debug Information</h1>";

// Check if theme is active
$current_theme = wp_get_theme();
echo "<h2>Theme Information</h2>";
echo "<p><strong>Active Theme:</strong> " . $current_theme->get('Name') . "</p>";
echo "<p><strong>Theme Directory:</strong> " . get_stylesheet_directory() . "</p>";
echo "<p><strong>Theme URL:</strong> " . get_stylesheet_directory_uri() . "</p>";

// Check CSS files
echo "<h2>CSS Files Check</h2>";
$css_files = [
    'Main Style' => get_stylesheet_directory() . '/style.css',
    'Custom Styles' => get_stylesheet_directory() . '/assets/css/custom-styles.css',
    'Responsive' => get_stylesheet_directory() . '/assets/css/responsive.css',
    'Calendar Styles' => get_stylesheet_directory() . '/assets/css/calendar-styles.css',
    'Booking System' => get_stylesheet_directory() . '/assets/css/booking-system.css'
];

foreach ($css_files as $name => $path) {
    $exists = file_exists($path);
    $status = $exists ? '✅' : '❌';
    echo "<p>$status <strong>$name:</strong> $path</p>";
    
    if ($exists) {
        $size = filesize($path);
        echo "<p style='margin-left: 20px;'>File size: " . number_format($size) . " bytes</p>";
    }
}

// Test CSS URLs
echo "<h2>CSS URLs (click to test)</h2>";
$css_urls = [
    'Main Style' => get_stylesheet_directory_uri() . '/style.css',
    'Custom Styles' => get_stylesheet_directory_uri() . '/assets/css/custom-styles.css',
    'Responsive' => get_stylesheet_directory_uri() . '/assets/css/responsive.css'
];

foreach ($css_urls as $name => $url) {
    echo "<p><strong>$name:</strong> <a href='$url' target='_blank'>$url</a></p>";
}

// Show what WordPress would enqueue
echo "<h2>WordPress Enqueue Test</h2>";
ob_start();
wp_head();
$head_content = ob_get_clean();

if (strpos($head_content, 'leadership-coach') !== false) {
    echo "<p>✅ Leadership Coach styles found in wp_head()</p>";
} else {
    echo "<p>❌ Leadership Coach styles NOT found in wp_head()</p>";
}

echo "<h3>Head Content Preview:</h3>";
echo "<pre>" . htmlspecialchars(substr($head_content, 0, 1000)) . "...</pre>";
?>