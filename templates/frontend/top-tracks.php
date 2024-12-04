<?php
if (!defined('ABSPATH')) {
    exit;
}

if (is_wp_error($tracks)) {
    ?>
    <div class="stt-error">
        <?php echo esc_html($tracks->get_error_message()); ?>
    </div>
    <?php
    return;
}
?>

<div class="stt-container">
    <div class="stt-tracks">
        <?php foreach ($tracks as $index => $track): ?>
            <div class="stt-track">
                <div class="stt-track-rank"><?php echo esc_html($index + 1); ?></div>
                <div class="stt-track-artwork">
                    <?php if ($track['image']): ?>
                        <img src="<?php echo esc_url($track['image']); ?>" alt="<?php echo esc_attr($track['title']); ?>" loading="lazy" />
                    <?php endif; ?>
                </div>
                <div class="stt-track-info">
                    <h3 class="stt-track-title">
                        <a href="<?php echo esc_url($track['url']); ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html($track['title']); ?>
                        </a>
                    </h3>
                    <p class="stt-track-artist"><?php echo esc_html($track['artist']); ?></p>
                    <p class="stt-track-album"><?php echo esc_html($track['album']); ?></p>
                </div>
                <?php if ($track['preview_url']): ?>
                    <div class="stt-track-preview">
                        <audio controls preload="none">
                            <source src="<?php echo esc_url($track['preview_url']); ?>" type="audio/mpeg">
                        </audio>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="stt-footer">
        <p class="stt-powered-by">
            <?php _e('Powered by', 'spotify-top-tracks'); ?>
            <a href="https://spotify.com" target="_blank" rel="noopener noreferrer">
                Spotify
            </a>
        </p>
    </div>
</div> 