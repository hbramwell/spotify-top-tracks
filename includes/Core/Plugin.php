<?php
namespace SpotifyTopTracks\Core;

/**
 * Main plugin class responsible for initializing all components
 */
class Plugin {
    /**
     * @var Plugin Single instance of this class
     */
    private static $instance;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->init_hooks();
        $this->init_components();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_shortcode('spotify_top_tracks', [$this, 'render_top_tracks']);
    }

    /**
     * Initialize plugin components
     */
    private function init_components() {
        new \SpotifyTopTracks\API\SpotifyClient();
        new \SpotifyTopTracks\Admin\Settings();
    }

    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Spotify Top Tracks', 'spotify-top-tracks'),
            __('Spotify Tracks', 'spotify-top-tracks'),
            'manage_options',
            'spotify-top-tracks',
            [$this, 'render_admin_page'],
            'dashicons-playlist-audio'
        );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style(
            'spotify-top-tracks-admin',
            STT_PLUGIN_URL . 'assets/css/admin.css',
            [],
            STT_VERSION
        );

        wp_enqueue_script(
            'spotify-top-tracks-admin',
            STT_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            STT_VERSION,
            true
        );
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'spotify-top-tracks-frontend',
            STT_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            STT_VERSION
        );
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        require_once STT_PLUGIN_DIR . 'templates/admin/settings.php';
    }

    /**
     * Render top tracks shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Rendered HTML
     */
    public function render_top_tracks($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts([
            'limit' => get_option('stt_default_track_limit', 10),
            'time_range' => get_option('stt_default_time_range', 'medium_term'),
        ], $atts, 'spotify_top_tracks');

        // Validate time_range
        if (!in_array($atts['time_range'], ['short_term', 'medium_term', 'long_term'])) {
            $atts['time_range'] = 'medium_term';
        }

        $client = new \SpotifyTopTracks\API\SpotifyClient();
        $tracks = $client->get_top_tracks(absint($atts['limit']), $atts['time_range']);
        
        ob_start();
        require STT_PLUGIN_DIR . 'templates/frontend/top-tracks.php';
        return ob_get_clean();
    }
} 