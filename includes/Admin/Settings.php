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
        register_setting('stt_settings', 'stt_default_track_limit', [
            'type' => 'integer',
            'default' => 10,
            'sanitize_callback' => function($value) {
                return max(1, min(50, absint($value)));
            },
        ]);
        register_setting('stt_settings', 'stt_default_time_range', [
            'type' => 'string',
            'default' => 'medium_term',
            'sanitize_callback' => function($value) {
                return in_array($value, ['short_term', 'medium_term', 'long_term']) ? $value : 'medium_term';
            },
        ]);

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

        add_settings_field(
            'stt_default_track_limit',
            __('Default Track Limit', 'spotify-top-tracks'),
            [$this, 'render_number_field'],
            'spotify-top-tracks',
            'stt_settings_section',
            ['label_for' => 'stt_default_track_limit']
        );

        add_settings_field(
            'stt_default_time_range',
            __('Default Time Range', 'spotify-top-tracks'),
            [$this, 'render_select_field'],
            'spotify-top-tracks',
            'stt_settings_section',
            [
                'label_for' => 'stt_default_time_range',
                'options' => [
                    'short_term' => __('Last 4 weeks', 'spotify-top-tracks'),
                    'medium_term' => __('Last 6 months', 'spotify-top-tracks'),
                    'long_term' => __('All time', 'spotify-top-tracks'),
                ],
            ]
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
        <div class="stt-shortcode-generator" style="margin-top: 20px; padding: 15px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px;">
            <h3><?php _e('Shortcode Generator', 'spotify-top-tracks'); ?></h3>
            <p><?php _e('Use this shortcode to display your Spotify top tracks:', 'spotify-top-tracks'); ?></p>
            <div class="stt-shortcode-display" style="background: #f0f0f1; padding: 10px; border-radius: 4px;">
                <code id="stt-generated-shortcode">[spotify_top_tracks]</code>
                <button type="button" class="button button-secondary" onclick="copyShortcode()" style="margin-left: 10px;">
                    <?php _e('Copy', 'spotify-top-tracks'); ?>
                </button>
            </div>
            <p class="description">
                <?php _e('The shortcode will use the default settings configured above.', 'spotify-top-tracks'); ?>
            </p>
        </div>
        <script>
        function copyShortcode() {
            const el = document.getElementById('stt-generated-shortcode');
            const range = document.createRange();
            range.selectNode(el);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            
            // Show feedback
            const button = el.nextElementSibling;
            const originalText = button.textContent;
            button.textContent = '<?php _e('Copied!', 'spotify-top-tracks'); ?>';
            setTimeout(() => {
                button.textContent = originalText;
            }, 2000);
        }

        // Update shortcode when settings change
        document.addEventListener('DOMContentLoaded', function() {
            const limitField = document.getElementById('stt_default_track_limit');
            const timeRangeField = document.getElementById('stt_default_time_range');
            const shortcodeEl = document.getElementById('stt-generated-shortcode');

            function updateShortcode() {
                const limit = limitField.value;
                const timeRange = timeRangeField.value;
                shortcodeEl.textContent = `[spotify_top_tracks limit="${limit}" time_range="${timeRange}"]`;
            }

            if (limitField && timeRangeField) {
                limitField.addEventListener('change', updateShortcode);
                timeRangeField.addEventListener('change', updateShortcode);
                updateShortcode(); // Initial update
            }
        });
        </script>
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
        $is_token = strpos($id, 'token') !== false || strpos($id, 'secret') !== false;
        ?>
        <input
            type="<?php echo $is_token ? 'password' : 'text'; ?>"
            id="<?php echo esc_attr($id); ?>"
            name="<?php echo esc_attr($id); ?>"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text"
        />
        <?php if ($is_token && !empty($value)) : ?>
            <p class="description">
                <?php _e('Token is set and securely stored', 'spotify-top-tracks'); ?>
            </p>
        <?php endif; ?>
        <?php
    }

    /**
     * Render number field
     *
     * @param array $args Field arguments
     */
    public function render_number_field($args) {
        $id = $args['label_for'];
        $value = get_option($id, 10);
        ?>
        <input
            type="number"
            id="<?php echo esc_attr($id); ?>"
            name="<?php echo esc_attr($id); ?>"
            value="<?php echo esc_attr($value); ?>"
            min="1"
            max="50"
            class="small-text"
        />
        <p class="description">
            <?php _e('Number of tracks to display (1-50)', 'spotify-top-tracks'); ?>
        </p>
        <?php
    }

    /**
     * Render select field
     *
     * @param array $args Field arguments
     */
    public function render_select_field($args) {
        $id = $args['label_for'];
        $value = get_option($id);
        ?>
        <select
            id="<?php echo esc_attr($id); ?>"
            name="<?php echo esc_attr($id); ?>"
            class="regular-text"
        >
            <?php foreach ($args['options'] as $key => $label) : ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }
} 