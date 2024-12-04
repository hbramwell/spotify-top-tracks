<?php
namespace SpotifyTopTracks\Admin;

/**
 * Handles plugin settings and configuration
 */
class Settings {
    /**
     * Initialize the settings page
     */
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('stt_settings', 'stt_spotify_client_id');
        register_setting('stt_settings', 'stt_spotify_client_secret');
        register_setting('stt_settings', 'stt_spotify_refresh_token');

        add_settings_section(
            'stt_settings_section',
            __('Spotify API Settings', 'spotify-top-tracks'),
            [$this, 'render_settings_section'],
            'spotify-top-tracks'
        );

        add_settings_field(
            'stt_spotify_client_id',
            __('Client ID', 'spotify-top-tracks'),
            [$this, 'render_text_field'],
            'spotify-top-tracks',
            'stt_settings_section',
            ['label_for' => 'stt_spotify_client_id']
        );

        add_settings_field(
            'stt_spotify_client_secret',
            __('Client Secret', 'spotify-top-tracks'),
            [$this, 'render_text_field'],
            'spotify-top-tracks',
            'stt_settings_section',
            ['label_for' => 'stt_spotify_client_secret']
        );

        add_settings_field(
            'stt_spotify_refresh_token',
            __('Refresh Token', 'spotify-top-tracks'),
            [$this, 'render_text_field'],
            'spotify-top-tracks',
            'stt_settings_section',
            ['label_for' => 'stt_spotify_refresh_token']
        );
    }

    /**
     * Render settings section description
     */
    public function render_settings_section() {
        ?>
        <p>
            <?php _e('Enter your Spotify API credentials below. You can obtain these by creating an application in the Spotify Developer Dashboard.', 'spotify-top-tracks'); ?>
            <a href="https://developer.spotify.com/dashboard" target="_blank">
                <?php _e('Visit Spotify Developer Dashboard', 'spotify-top-tracks'); ?>
            </a>
        </p>
        <?php
    }

    /**
     * Render text field
     *
     * @param array $args Field arguments
     */
    public function render_text_field($args) {
        $id = $args['label_for'];
        $value = get_option($id);
        ?>
        <input
            type="text"
            id="<?php echo esc_attr($id); ?>"
            name="<?php echo esc_attr($id); ?>"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text"
        />
        <?php
    }
} 