<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="notice notice-info">
        <p>
            <?php _e('Use the shortcode <code>[spotify_top_tracks]</code> to display your top tracks on any page or post.', 'spotify-top-tracks'); ?>
        </p>
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields('stt_settings');
        do_settings_sections('spotify-top-tracks');
        submit_button();
        ?>
    </form>

    <div class="card">
        <h2><?php _e('How to Get Started', 'spotify-top-tracks'); ?></h2>
        <ol>
            <li><?php _e('Create a new application in the Spotify Developer Dashboard', 'spotify-top-tracks'); ?></li>
            <li><?php _e('Set the redirect URI to your website URL', 'spotify-top-tracks'); ?></li>
            <li><?php _e('Copy the Client ID and Client Secret to the fields above', 'spotify-top-tracks'); ?></li>
            <li><?php _e('Follow the authentication process to get your refresh token', 'spotify-top-tracks'); ?></li>
        </ol>
        <p>
            <a href="https://developer.spotify.com/documentation/web-api/concepts/authorization" target="_blank" class="button button-secondary">
                <?php _e('Learn More About Authentication', 'spotify-top-tracks'); ?>
            </a>
        </p>
    </div>
</div> 