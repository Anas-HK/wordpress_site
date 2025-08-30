<?php

/**
 * PHP Syntax Test
 * Place this in your WordPress root and visit it to test PHP syntax
 */

echo "<h1>PHP Syntax Test</h1>";

// Test basic PHP
echo "<p>✅ Basic PHP working</p>";

// Test WordPress loading
$wp_config_path = __DIR__ . '/wp-config.php';
if (file_exists($wp_config_path)) {
    echo "<p>✅ wp-config.php found</p>";

    // Try to load WordPress
    try {
        require_once($wp_config_path);
        echo "<p>✅ WordPress config loaded</p>";

        // Test theme functions
        $theme_functions = get_stylesheet_directory() . '/functions.php';
        if (file_exists($theme_functions)) {
            echo "<p>✅ Theme functions.php found</p>";

            // Check for syntax errors by parsing the file
            $content = file_get_contents($theme_functions);
            $tokens = token_get_all($content);

            if ($tokens !== false) {
                echo "<p>✅ functions.php syntax appears valid</p>";
            } else {
                echo "<p>❌ functions.php has syntax errors</p>";
            }
        } else {
            echo "<p>❌ Theme functions.php not found</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error loading WordPress: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ wp-config.php not found</p>";
}

// Show PHP error reporting status
echo "<h2>PHP Configuration</h2>";
echo "<p><strong>Error Reporting:</strong> " . (error_reporting() ? 'Enabled' : 'Disabled') . "</p>";
echo "<p><strong>Display Errors:</strong> " . (ini_get('display_errors') ? 'On' : 'Off') . "</p>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";

// Show recent PHP errors if log exists
$error_log = __DIR__ . '/wp-content/debug.log';
if (file_exists($error_log)) {
    echo "<h2>Recent Errors</h2>";
    $errors = file_get_contents($error_log);
    $recent_errors = array_slice(explode("\n", $errors), -10);
    echo "<pre>" . htmlspecialchars(implode("\n", $recent_errors)) . "</pre>";
}
