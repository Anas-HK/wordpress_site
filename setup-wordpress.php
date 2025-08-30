<?php
/**
 * WordPress Setup Script
 * Run this once to set up pages and basic content
 */

// Load WordPress
require_once('wp-config.php');

echo "<h1>WordPress Setup for Leadership Coach</h1>";

// Check if user is admin
if (!current_user_can('administrator')) {
    echo "<p style='color: red;'>❌ You must be logged in as an administrator to run this setup.</p>";
    echo "<p><a href='" . admin_url() . "'>Go to Admin</a></p>";
    exit;
}

echo "<h2>Creating Pages...</h2>";

// Pages to create
$pages = [
    'Home' => [
        'title' => 'Home',
        'content' => 'Welcome to our Leadership Coaching website. This page uses the front-page.php template.',
        'template' => '',
        'is_front_page' => true
    ],
    'About' => [
        'title' => 'About',
        'content' => '<h2>About Our Leadership Coach</h2>
        <p>With over 15 years of experience in leadership development and organizational transformation, our coach is passionate about helping leaders unlock their full potential.</p>
        <p>Our approach combines proven methodologies with personalized strategies to create lasting change in both individual leaders and their organizations.</p>',
        'template' => ''
    ],
    'Services' => [
        'title' => 'Services',
        'content' => '<h2>Our Coaching Services</h2>
        <h3>Executive Coaching</h3>
        <p>One-on-one coaching sessions designed for senior leaders and executives.</p>
        <h3>Team Leadership</h3>
        <p>Build stronger, more effective teams through better leadership practices.</p>
        <h3>Strategic Planning</h3>
        <p>Develop clear vision and actionable strategies for growth.</p>',
        'template' => ''
    ],
    'Calendar' => [
        'title' => 'Book Appointment',
        'content' => '<h2>Schedule Your Coaching Session</h2>
        <p>Select a date and time that works for you. We look forward to working together!</p>',
        'template' => 'page-calendar.php'
    ],
    'Contact' => [
        'title' => 'Contact Us',
        'content' => '<h2>Get in Touch</h2>
        <p>Ready to start your leadership transformation? Contact us today to learn more about our coaching services.</p>',
        'template' => 'page-contact.php'
    ]
];

$created_pages = [];

foreach ($pages as $slug => $page_data) {
    // Check if page already exists
    $existing_page = get_page_by_path($slug);
    
    if ($existing_page) {
        echo "<p>✅ Page '$slug' already exists (ID: {$existing_page->ID})</p>";
        $created_pages[$slug] = $existing_page->ID;
        continue;
    }
    
    // Create the page
    $page_id = wp_insert_post([
        'post_title' => $page_data['title'],
        'post_content' => $page_data['content'],
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_name' => strtolower($slug)
    ]);
    
    if ($page_id && !is_wp_error($page_id)) {
        echo "<p>✅ Created page '$slug' (ID: $page_id)</p>";
        $created_pages[$slug] = $page_id;
        
        // Set page template if specified
        if (!empty($page_data['template'])) {
            update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            echo "<p style='margin-left: 20px;'>📄 Set template: {$page_data['template']}</p>";
        }
        
        // Set as front page if specified
        if (isset($page_data['is_front_page']) && $page_data['is_front_page']) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $page_id);
            echo "<p style='margin-left: 20px;'>🏠 Set as front page</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Failed to create page '$slug'</p>";
    }
}

// Create navigation menu
echo "<h2>Setting up Navigation Menu...</h2>";

$menu_name = 'Primary Menu';
$menu_exists = wp_get_nav_menu_object($menu_name);

if (!$menu_exists) {
    $menu_id = wp_create_nav_menu($menu_name);
    echo "<p>✅ Created menu '$menu_name' (ID: $menu_id)</p>";
} else {
    $menu_id = $menu_exists->term_id;
    echo "<p>✅ Menu '$menu_name' already exists (ID: $menu_id)</p>";
}

// Add pages to menu
$menu_items = ['Home', 'About', 'Services', 'Calendar', 'Contact'];
foreach ($menu_items as $item) {
    if (isset($created_pages[strtolower($item)])) {
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => $item,
            'menu-item-object' => 'page',
            'menu-item-object-id' => $created_pages[strtolower($item)],
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'
        ]);
        echo "<p style='margin-left: 20px;'>📋 Added '$item' to menu</p>";
    }
}

// Set menu location
$locations = get_theme_mod('nav_menu_locations');
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
echo "<p>✅ Assigned menu to primary location</p>";

echo "<h2>Setup Complete!</h2>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li><a href='" . home_url() . "'>Visit your homepage</a></li>";
echo "<li><a href='" . admin_url('nav-menus.php') . "'>Customize your menu</a></li>";
echo "<li><a href='" . admin_url('customize.php') . "'>Customize your theme</a></li>";
echo "</ul>";

echo "<h2>Created Pages:</h2>";
echo "<ul>";
foreach ($created_pages as $slug => $page_id) {
    $page_url = get_permalink($page_id);
    echo "<li><a href='$page_url'>$slug</a> (ID: $page_id)</li>";
}
echo "</ul>";
?>