<?php
/**
 * Database Test Script
 * Place this in your WordPress root directory and visit it directly
 */

// Load WordPress
require_once('wp-config.php');
require_once('wp-includes/wp-db.php');

global $wpdb;

echo "<h1>Database Test Results</h1>";

// Test database connection
echo "<h2>Connection Test</h2>";
if ($wpdb->last_error) {
    echo "<p style='color: red;'>❌ Database Error: " . $wpdb->last_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ Database Connected Successfully</p>";
}

// Check if appointments table exists
$table_name = $wpdb->prefix . 'coaching_appointments';
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;

echo "<h2>Appointments Table</h2>";
if ($table_exists) {
    echo "<p style='color: green;'>✅ Table '$table_name' exists</p>";
    
    // Show table structure
    $columns = $wpdb->get_results("DESCRIBE $table_name");
    echo "<h3>Table Structure:</h3><ul>";
    foreach ($columns as $column) {
        echo "<li><strong>{$column->Field}</strong>: {$column->Type}</li>";
    }
    echo "</ul>";
    
    // Count records
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    echo "<p>Records in table: <strong>$count</strong></p>";
} else {
    echo "<p style='color: red;'>❌ Table '$table_name' does not exist</p>";
    echo "<p>Try activating the theme or running the setup function.</p>";
}

// Test appointment model
echo "<h2>Appointment Model Test</h2>";
$theme_dir = get_stylesheet_directory();
$model_file = $theme_dir . '/inc/models/class-appointment.php';

if (file_exists($model_file)) {
    echo "<p style='color: green;'>✅ Appointment model file found</p>";
    
    require_once($model_file);
    
    if (class_exists('Leadership_Coach_Appointment')) {
        echo "<p style='color: green;'>✅ Appointment class loaded</p>";
        
        $appointment = new Leadership_Coach_Appointment();
        $slots = $appointment->get_available_slots(date('Y-m-d', strtotime('+1 day')));
        echo "<p>Available slots for tomorrow: <strong>" . count($slots) . "</strong></p>";
        if (!empty($slots)) {
            echo "<p>Sample slots: " . implode(', ', array_slice($slots, 0, 3)) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Appointment class not found</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Appointment model file not found at: $model_file</p>";
}

echo "<hr><p><a href='" . home_url() . "'>← Back to Site</a></p>";
?>