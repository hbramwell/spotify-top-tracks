<?php
/**
 * Plugin Name: Spotify Top Tracks
 * Plugin URI: https://example.com/spotify-top-tracks
 * Description: Display your Spotify top tracks with a beautiful, minimal interface
 * Version: 1.0.0
 * Author: Pixam Studio
 * Author URI: https://pixamstudio.com
 * Text Domain: spotify-top-tracks
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('STT_VERSION', '1.0.0');
define('STT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('STT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoloader for classes
spl_autoload_register(function ($class) {
    $prefix = 'SpotifyTopTracks\\';
    $base_dir = STT_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
function stt_init() {
    // Initialize plugin components
    new SpotifyTopTracks\Core\Plugin();
}
add_action('plugins_loaded', 'stt_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    // Create necessary database tables and options
    require_once STT_PLUGIN_DIR . 'includes/Core/Activator.php';
    SpotifyTopTracks\Core\Activator::activate();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    // Clean up if necessary
    require_once STT_PLUGIN_DIR . 'includes/Core/Deactivator.php';
    SpotifyTopTracks\Core\Deactivator::deactivate();
}); 