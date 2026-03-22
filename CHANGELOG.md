# Changelog

All notable changes to Geo Posts are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/).

## [0.4.0] - 2026-03-22

### Changed
- Absorbed pp-core framework into plugin-owned classes (`Component`, `Settings_Core`, `Meta_Box`)
- Admin UI helper functions now use `ppgp_` prefix (`ppgp_get_text_input_html`, `ppgp_get_checkbox_toggle_html`)
- Admin assets moved from `pp-assets/` to `assets/admin/`
- Admin asset enqueue now handled directly in `Admin_Hooks` instead of via pp-core

### Added
- `includes/class-component.php` — base class for plugin components
- `includes/class-settings-core.php` — abstract settings base class
- `includes/class-meta-box.php` — abstract meta box base class
- `includes/admin-ui-helpers.php` — form rendering helper functions
- `phpcs.xml` — WordPress coding standards configuration
- `README.md`, `readme.txt`, `CHANGELOG.md`, and `docs/` directory

### Removed
- Direct dependency on `pp-core.php` and `pp-assets/` (archived for reference)

## [0.3.0] - 2026-03-22

### Added
- Initial tracked version
- `[single_post_map]` shortcode — renders a Google Map for a single post
- `[multi_post_map]` shortcode — renders a Google Map with markers for all geo-enabled posts
- Settings page under Settings > Geo Posts for the Google Maps API key
- Geo Post meta box with latitude, longitude, and enable/disable toggle
- Support for configurable post types via `ppgp_geo_post_types` option
