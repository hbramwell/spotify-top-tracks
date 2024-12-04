# Spotify Top Tracks WordPress Plugin

Display your Spotify top tracks with a beautiful, minimal interface. This plugin allows you to showcase your most played tracks from Spotify directly on your WordPress site.

## Features

- 🎵 Display your top Spotify tracks
- ⏱️ Choose between different time ranges (4 weeks, 6 months, or all time)
- 🔢 Customize the number of tracks to display (1-50)
- 🔒 Secure storage of Spotify API credentials
- 📱 Responsive design that looks great on all devices
- 🎨 Beautiful, minimal interface
- 🚀 Easy to set up and use

## Installation

1. Download the plugin zip file
2. Upload it to your WordPress site through the plugins page
3. Activate the plugin
4. Go to "Spotify Tracks" in your WordPress admin menu

## Configuration

1. Create a Spotify Application:
   - Visit the [Spotify Developer Dashboard](https://developer.spotify.com/dashboard)
   - Create a new application
   - Get your Client ID and Client Secret
   - Set up your redirect URI (your-site.com/wp-admin/admin.php?page=spotify-top-tracks)

2. Configure the Plugin:
   - Go to WordPress Admin → Spotify Tracks
   - Enter your Spotify API credentials (Client ID and Client Secret)
   - Enter your Refresh Token
   - Set your preferred default settings:
     - Number of tracks to display (1-50)
     - Time range (Last 4 weeks, Last 6 months, or All time)

3. Use the Plugin:
   - The settings page includes a shortcode generator
   - Copy the generated shortcode
   - Paste it into any post or page where you want to display your top tracks

## Usage

Use the shortcode generator in the plugin settings to create your shortcode, or manually create it with these parameters:

```
[spotify_top_tracks limit="20" time_range="medium_term"]
```

Parameters:
- `limit`: Number of tracks to display (1-50)
- `time_range`: Time period for top tracks
  - `short_term`: Last 4 weeks
  - `medium_term`: Last 6 months (default)
  - `long_term`: All time

## Customization

The plugin includes two CSS files that you can customize:
- `assets/css/admin.css` - Styles for the admin interface
- `assets/css/frontend.css` - Styles for the frontend display

## Security

- API credentials are stored securely in WordPress options
- Sensitive tokens are obscured in the admin interface
- All data is properly sanitized and escaped

## Support

For support, feature requests, or bug reports, please visit the [GitHub repository](https://github.com/pixamstudio/spotify-top-tracks).

## Credits

Created by [Pixam Studio](https://pixamstudio.com)

## License

GPL v2 or later