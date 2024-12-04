# Spotify Top Tracks WordPress Plugin

Display your Spotify top tracks on your WordPress site with a beautiful, minimal interface.

## Features

- Clean and modern design
- Responsive layout
- Audio preview support
- Easy setup with shortcode
- Secure API authentication
- Caching for better performance

## Installation

1. Download the plugin zip file
2. Go to WordPress admin > Plugins > Add New > Upload Plugin
3. Upload the zip file and activate the plugin
4. Go to Spotify Tracks in the admin menu to configure

## Configuration

1. Create a new application in the [Spotify Developer Dashboard](https://developer.spotify.com/dashboard)
2. Set the redirect URI to your website URL
3. Copy the Client ID and Client Secret
4. Go to WordPress admin > Spotify Tracks
5. Enter your Spotify API credentials
6. Follow the authentication process to get your refresh token

## Usage

Use the shortcode `[spotify_top_tracks]` to display your top tracks on any page or post.

Example:
```php
[spotify_top_tracks]
```

## Customization

The plugin uses minimal, clean styling that should work with most themes. You can customize the appearance by adding custom CSS to your theme.

## Security

- All API credentials are stored securely in WordPress options
- API requests are made server-side
- Data is properly sanitized and escaped
- Nonce verification for admin actions
- Capability checks for administrative functions

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Spotify Developer account
- HTTPS enabled (required for Spotify API)

## Support

For support, feature requests, or bug reports, please visit the [plugin support forum](https://wordpress.org/support/plugin/spotify-top-tracks/).

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Built with love by Pixam Studio
- Powered by [Spotify Web API](https://developer.spotify.com/documentation/web-api/) 