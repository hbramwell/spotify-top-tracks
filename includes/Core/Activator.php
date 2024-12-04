<?php
namespace SpotifyTopTracks\Core;

/**
 * Fired during plugin activation
 */
class Activator {
    /**
     * Activate the plugin
     *
     * @return void
     */
    public static function activate() {
        // Add default options
        add_option('stt_spotify_client_id', '');
        add_option('stt_spotify_client_secret', '');
        add_option('stt_spotify_refresh_token', '');

        // Set plugin version
        add_option('stt_version', STT_VERSION);

        // Flush rewrite rules
        flush_rewrite_rules();
    }
} 