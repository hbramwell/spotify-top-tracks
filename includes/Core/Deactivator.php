<?php
namespace SpotifyTopTracks\Core;

/**
 * Fired during plugin deactivation
 */
class Deactivator {
    /**
     * Deactivate the plugin
     *
     * @return void
     */
    public static function deactivate() {
        // Clean up transients
        delete_transient('stt_access_token');

        // Flush rewrite rules
        flush_rewrite_rules();
    }
} 