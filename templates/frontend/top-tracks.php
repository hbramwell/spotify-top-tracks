<?php
/**
 * Frontend template for displaying Spotify top tracks
 * 
 * @package SpotifyTopTracks
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Debug information (only visible to administrators)
if (current_user_can('manage_options')) {
    echo '<!-- Debug Info: ';
    if (is_wp_error($tracks)) {
        echo 'WP_Error: ' . esc_html($tracks->get_error_message());
    } elseif (!is_array($tracks)) {
        echo 'Tracks is not an array. Type: ' . gettype($tracks);
    } else {
        echo 'Number of tracks: ' . count($tracks);
    }
    echo ' -->';
}

// Check for WP_Error
if (is_wp_error($tracks)) {
    ?>
    <div class="stt-container">
        <div class="stt-error">
            <p><?php echo esc_html($tracks->get_error_message()); ?></p>
        </div>
    </div>
    <?php
    return;
}

// Ensure $tracks is an array
if (!is_array($tracks)) {
    $tracks = array();
}

// Debug tracks data for administrators
if (current_user_can('manage_options') && !empty($tracks)) {
    echo '<!-- First track data: ';
    print_r($tracks[0]);
    echo ' -->';
}
?>

<div class="stt-container">
    <?php if (empty($tracks)) : ?>
        <div class="stt-error">
            <p><?php _e('No tracks found. Please make sure your Spotify credentials are configured correctly.', 'spotify-top-tracks'); ?></p>
        </div>
    <?php else : ?>
        <div class="stt-tracks-grid">
            <?php foreach ($tracks as $track) : ?>
                <?php
                // Validate required track data
                if (!isset($track['album']['images'][0]['url']) || 
                    !isset($track['name']) || 
                    !isset($track['external_urls']['spotify']) ||
                    !isset($track['artists']) ||
                    !is_array($track['artists'])) {
                    if (current_user_can('manage_options')) {
                        echo '<!-- Invalid track data: ';
                        print_r($track);
                        echo ' -->';
                    }
                    continue;
                }
                ?>
                <div class="stt-track-card">
                    <div class="stt-track-artwork">
                        <img src="<?php echo esc_url($track['album']['images'][0]['url']); ?>" 
                             alt="<?php echo esc_attr($track['name']); ?> album artwork" 
                             loading="lazy">
                        <?php if (!empty($track['preview_url'])) : ?>
                            <button class="stt-play-button" 
                                    data-preview-url="<?php echo esc_url($track['preview_url']); ?>"
                                    aria-label="Play preview">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="stt-track-info">
                        <h3 class="stt-track-title">
                            <a href="<?php echo esc_url($track['external_urls']['spotify']); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer">
                                <?php echo esc_html($track['name']); ?>
                            </a>
                        </h3>
                        <p class="stt-track-artist">
                            <?php 
                            $artists = array_map(function($artist) {
                                if (!isset($artist['external_urls']['spotify']) || !isset($artist['name'])) {
                                    return esc_html($artist['name'] ?? 'Unknown Artist');
                                }
                                return sprintf(
                                    '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                                    esc_url($artist['external_urls']['spotify']),
                                    esc_html($artist['name'])
                                );
                            }, $track['artists']);
                            echo implode(', ', $artists);
                            ?>
                        </p>
                        <div class="stt-track-meta">
                            <span class="stt-track-album">
                                <?php if (isset($track['album']['external_urls']['spotify']) && isset($track['album']['name'])) : ?>
                                    <a href="<?php echo esc_url($track['album']['external_urls']['spotify']); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer">
                                        <?php echo esc_html($track['album']['name']); ?>
                                    </a>
                                <?php endif; ?>
                            </span>
                            <span class="stt-track-duration">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                                    <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"/>
                                    <path d="M13 7h-2v6l4.24 2.54.76-1.27-3-1.79z"/>
                                </svg>
                                <?php 
                                if (isset($track['duration_ms'])) {
                                    $duration = floor($track['duration_ms'] / 1000);
                                    echo esc_html(sprintf('%d:%02d', floor($duration / 60), $duration % 60));
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentAudio = null;
    let currentButton = null;

    document.querySelectorAll('.stt-play-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const previewUrl = this.dataset.previewUrl;
            
            if (!previewUrl) {
                return;
            }
            
            // Stop current playing audio if exists
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                currentButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M8 5v14l11-7z"/>
                </svg>`;
            }

            // If clicking same button, just stop
            if (currentButton === this) {
                currentAudio = null;
                currentButton = null;
                return;
            }

            // Play new audio
            currentAudio = new Audio(previewUrl);
            currentButton = this;
            
            this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
            </svg>`;

            currentAudio.play().catch(error => {
                console.error('Error playing audio:', error);
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M8 5v14l11-7z"/>
                </svg>`;
                currentAudio = null;
                currentButton = null;
            });

            currentAudio.addEventListener('ended', () => {
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                    <path d="M8 5v14l11-7z"/>
                </svg>`;
                currentAudio = null;
                currentButton = null;
            });
        });
    });
});</script> 