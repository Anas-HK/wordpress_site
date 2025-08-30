<?php
/**
 * Laragon WordPress Debug Helper
 * Place in WordPress root directory
 */

// Load WordPress
require_once('wp-config.php');

echo "<h1>Laragon WordPress Debug</h1>";

// Environment info
echo "<h2>Environment</h2>";
echo "<p><strong>WordPress URL:</strong> " . home_url() . "</p>";
echo "<p><strong>WordPress Path:</strong> " . ABSPATH . "</p>";
echo "<p><strong>Theme Path:</strong> " . get_stylesheet_directory() . "</p>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";

// Theme info
echo "<h2>Theme Status</h2>";
$theme = wp_get_theme();
echo "<p><strong>Active Theme:</strong> " . $theme->get('Name') . "</p>";
echo "<p><strong>Theme Version:</strong> " . $theme->get('Version') . "</p>";
echo "<p><strong>Theme Directory:</strong> " . $theme->get_stylesheet_directory() . "</p>";

// File checks
echo "<h2>Required Files</h2>";
$files = [
    'style.css',
    'functions.php',
    'page-calendar.php',
    'page-contact.php',
    'inc/models/class-appointment.php',
    'inc/js/calendar-widget.js',
    'inc/js/booking-system.js',
    'assets/css/calendar-styles.css',
    'assets/css/booking-system.css'
];

foreach ($files as $file) {
    $path = get_stylesheet_directory() . '/' . $file;
    $exists = file_exists($path);
    $status = $exists ? '✅' : '❌';
    echo "<p>$status <strong>$file</strong></p>";
}

// Database check
echo "<h2>Database</h2>";
global $wpdb;
$table = $wpdb->prefix . 'coaching_appointments';
$exists = $wpdb->get_var("SHOW TABLES LIKE '$table'") == $table;
echo "<p>" . ($exists ? '✅' : '❌') . " Appointments table</p>";

// Test pages
echo "<h2>Test Pages</h2>";
$pages = get_pages();
foreach ($pages as $page) {
    $template = get_page_template_slug($page->ID);
    if (in_array($template, ['page-calendar.php', 'page-contact.php'])) {
        echo "<p>✅ <a href='" . get_permalink($page->ID) . "'>{$page->post_title}</a> (Template: $template)</p>";
    }
}

echo "<hr>";
echo "<h2>Quick Actions</h2>";
echo "<p><a href='" . admin_url('themes.php') . "'>Manage Themes</a></p>";
echo "<p><a href='" . admin_url('edit.php?post_type=page') . "'>Manage Pages</a></p>";
echo "<p><a href='" . home_url() . "'>View Site</a></p>";
?>