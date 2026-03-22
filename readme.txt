=== Geo Posts ===
Contributors: headwalluk
Tags: maps, google maps, geolocation, latitude, longitude
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Stable tag: 0.4.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Add latitude/longitude metadata to posts and render Google Maps via shortcodes.

== Description ==

Geo Posts lets you attach geographic coordinates to any WordPress post type and display interactive Google Maps on your site using simple shortcodes.

**Features:**

* Add latitude and longitude to any post type via a meta box
* Display a map for a single post with `[single_post_map]`
* Display a map of all geo-enabled posts with `[multi_post_map]`
* Choose which post types support geo data from the settings page
* Customisable map zoom, centre point, and info windows

**Requirements:**

* A Google Maps API key with the Maps JavaScript API and Places API enabled

== Installation ==

1. Upload the `geo-posts` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Geo Posts and enter your Google Maps API key
4. Edit any post and use the Geo Post meta box to add coordinates
5. Use `[single_post_map]` or `[multi_post_map]` shortcodes to display maps

== Frequently Asked Questions ==

= How do I get a Google Maps API key? =

Visit the [Google Cloud Console](https://console.cloud.google.com/), create a project, and enable the Maps JavaScript API and Places API. Then create an API key under Credentials.

= Which post types are supported? =

By default, only standard Posts have geo data enabled. You can configure additional post types from Settings > Geo Posts.

= Can I customise the map appearance? =

Yes. Both shortcodes accept attributes for zoom level, centre coordinates, CSS class, and whether to show info windows. See the shortcode documentation for details.

== Changelog ==

= 0.4.0 =
* Absorbed pp-core framework into plugin — no more external dependencies
* Added plugin-owned base classes (Component, Settings_Core, Meta_Box)
* Added admin UI helper functions with ppgp_ prefix
* Moved admin assets to assets/admin/
* Added phpcs.xml for WordPress coding standards
* Archived original pp-core.php and pp-assets/ for reference

= 0.3.0 =
* Initial tracked version
* Shortcodes: [single_post_map], [multi_post_map]
* Settings page for Google Maps API key
* Geo Post meta box with lat/lon fields and enable toggle
