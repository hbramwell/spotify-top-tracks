<?php
namespace SpotifyTopTracks\API;

/**
 * Handles all Spotify API interactions
 */
class SpotifyClient {
    /**
     * @var string Spotify API base URL
     */
    private const API_BASE = 'https://api.spotify.com/v1';

    /**
     * @var string Token endpoint
     */
    private const TOKEN_ENDPOINT = 'https://accounts.spotify.com/api/token';

    /**
     * Get access token using refresh token
     *
     * @return string|WP_Error
     */
    private function get_access_token() {
        $client_id = get_option('stt_spotify_client_id');
        $client_secret = get_option('stt_spotify_client_secret');
        $refresh_token = get_option('stt_spotify_refresh_token');

        if (!$client_id || !$client_secret || !$refresh_token) {
            return new \WP_Error('missing_credentials', __('Spotify credentials are not configured', 'spotify-top-tracks'));
        }

        $basic = base64_encode($client_id . ':' . $client_secret);

        $response = wp_remote_post(self::TOKEN_ENDPOINT, [
            'headers' => [
                'Authorization' => 'Basic ' . $basic,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
            ],
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return $body['access_token'] ?? new \WP_Error('invalid_response', __('Invalid response from Spotify', 'spotify-top-tracks'));
    }

    /**
     * Get user's top tracks
     *
     * @param int $limit Number of tracks to return
     * @return array|WP_Error Array of track data or WP_Error on failure
     */
    public function get_top_tracks($limit = 10) {
        $access_token = $this->get_access_token();
        if (is_wp_error($access_token)) {
            return $access_token;
        }

        $response = wp_remote_get(self::API_BASE . '/me/top/tracks', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
            ],
            'body' => [
                'limit' => $limit,
                'time_range' => 'short_term',
            ],
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!isset($body['items']) || !is_array($body['items'])) {
            return new \WP_Error('invalid_response', __('Invalid response from Spotify', 'spotify-top-tracks'));
        }

        // Return the raw track data as our template expects it
        return array_map(function($track) {
            // Ensure all required fields exist and are properly sanitized
            return [
                'name' => sanitize_text_field($track['name'] ?? ''),
                'artists' => array_map(function($artist) {
                    return [
                        'name' => sanitize_text_field($artist['name'] ?? ''),
                        'external_urls' => [
                            'spotify' => esc_url($artist['external_urls']['spotify'] ?? ''),
                        ],
                    ];
                }, $track['artists'] ?? []),
                'album' => [
                    'name' => sanitize_text_field($track['album']['name'] ?? ''),
                    'images' => array_map(function($image) {
                        return [
                            'url' => esc_url($image['url'] ?? ''),
                        ];
                    }, $track['album']['images'] ?? []),
                    'external_urls' => [
                        'spotify' => esc_url($track['album']['external_urls']['spotify'] ?? ''),
                    ],
                ],
                'duration_ms' => absint($track['duration_ms'] ?? 0),
                'external_urls' => [
                    'spotify' => esc_url($track['external_urls']['spotify'] ?? ''),
                ],
                'preview_url' => esc_url($track['preview_url'] ?? ''),
            ];
        }, $body['items']);
    }
} 