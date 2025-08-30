<?php
/**
 * Debug Information Page
 * Add this as a WordPress page template to check system status
 */

// Template Name: Debug Info

get_header(); ?>

<div class="debug-info">
    <h1>WordPress Coaching Site Debug Info</h1>
    
    <h2>Theme Status</h2>
    <p><strong>Active Theme:</strong> <?php echo wp_get_theme()->get('Name'); ?></p>
    <p><strong>Theme Version:</strong> <?php echo wp_get_theme()->get('Version'); ?></p>
    
    <h2>Database Tables</h2>
    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'coaching_appointments';
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    ?>
    <p><strong>Appointments Table:</strong> <?php echo $table_exists ? '✅ Exists' : '❌ Missing'; ?></p>
    
    <h2>Required Files</h2>
    <?php
    $required_files = [
        'inc/models/class-appointment.php',
        'inc/js/calendar-widget.js',
        'inc/js/booking-system.js',
        'assets/css/calendar-styles.css',
        'page-calendar.php',
        'page-contact.php'
    ];
    
    foreach ($required_files as $file) {
        $file_path = get_stylesheet_directory() . '/' . $file;
        $exists = file_exists($file_path);
        echo "<p><strong>$file:</strong> " . ($exists ? '✅ Found' : '❌ Missing') . "</p>";
    }
    ?>
    
    <h2>WordPress Info</h2>
    <p><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></p>
    <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
    <p><strong>MySQL Version:</strong> <?php echo $wpdb->db_version(); ?></p>
    
    <h2>Test Links</h2>
    <ul>
        <li><a href="<?php echo home_url('/calendar'); ?>">Calendar Page</a></li>
        <li><a href="<?php echo home_url('/contact'); ?>">Contact Page</a></li>
        <li><a href="<?php echo admin_url('admin-ajax.php?action=get_available_time_slots&date=2024-12-01&nonce=' . wp_create_nonce('booking_system_nonce')); ?>">Test AJAX (Time Slots)</a></li>
    </ul>
</div>

<?php get_footer(); ?>