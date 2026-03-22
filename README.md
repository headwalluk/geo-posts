# Geo Posts

[![WordPress Plugin Version](https://img.shields.io/badge/version-0.4.0-blue)](https://github.com/headwalluk/geo-posts)
[![License: GPLv3](https://img.shields.io/badge/license-GPLv3-green)](https://www.gnu.org/licenses/gpl-3.0.html)
[![PHP 8.0+](https://img.shields.io/badge/PHP-8.0%2B-8892BF)](https://www.php.net/)
[![WordPress 6.0+](https://img.shields.io/badge/WordPress-6.0%2B-21759B)](https://wordpress.org/)

A WordPress plugin that adds latitude/longitude metadata to posts and renders Google Maps via shortcodes.

## Features

- Add geographic coordinates (latitude/longitude) to any post type
- Render single-post maps with the `[single_post_map]` shortcode
- Render multi-post maps showing all geo-enabled posts with `[multi_post_map]`
- Configure which post types support geo data from the settings page
- Uses the Google Maps JavaScript API

## Quick Start

1. Upload the `geo-posts` folder to `wp-content/plugins/`
2. Activate the plugin in WordPress
3. Go to **Settings > Geo Posts** and enter your Google Maps API key
4. Edit a post and use the **Geo Post** meta box to add coordinates
5. Add `[single_post_map]` to a post or `[multi_post_map]` to any page

## Documentation

- [Installation Guide](docs/installation.md)
- [Shortcode Reference](docs/shortcodes.md)
- [Settings & Configuration](docs/settings.md)
- [Google Maps API Setup](docs/google-maps-api.md)

## Requirements

- PHP 8.0 or higher
- WordPress 6.0 or higher
- A Google Maps API key with Maps JavaScript API and Places API enabled

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a full list of changes.

## License

This plugin is licensed under the [GNU General Public License v3.0](LICENSE).
