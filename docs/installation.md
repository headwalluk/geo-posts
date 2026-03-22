# Installation

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- A Google Maps API key (see [Google Maps API Setup](google-maps-api.md))

## Manual Installation

1. Download the plugin files
2. Upload the `geo-posts` folder to `/wp-content/plugins/`
3. Go to **Plugins** in your WordPress admin and activate **Geo Posts**
4. Navigate to **Settings > Geo Posts** and enter your Google Maps API key

## First Steps

Once activated:

1. **Add your API key** — Go to Settings > Geo Posts and paste your Google Maps API key
2. **Enable a post** — Edit any post, find the **Geo Post** meta box, tick "Enable geo-data", and enter latitude/longitude coordinates
3. **Add a map** — Insert `[single_post_map]` into the post content to display a map, or add `[multi_post_map]` to any page to show all geo-enabled posts on one map

## Verify It Works

After adding coordinates to a post and inserting the `[single_post_map]` shortcode, view the post on the front end. You should see an interactive Google Map with a marker at the coordinates you entered.

If the map doesn't appear, check:

- Your API key is correct and has the Maps JavaScript API enabled
- The post has "Enable geo-data" ticked and valid coordinates
- There are no JavaScript errors in the browser console (F12 > Console)
