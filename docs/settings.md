# Settings & Configuration

## Accessing the Settings Page

Navigate to **Settings > Geo Posts** in the WordPress admin.

## Google Maps API Key

Enter your Google Maps API key in the text field. This key is used to load the Google Maps JavaScript API on the front end of your site.

The key must have the following APIs enabled:

- **Maps JavaScript API** — required for rendering maps
- **Places API (New)** — required for place-related features

See [Google Maps API Setup](google-maps-api.md) for step-by-step instructions on creating a key.

## Post Types

By default, only standard **Posts** have geo data enabled. The geo meta box will appear on the post edit screen for all enabled post types.

> **Note:** A settings UI for configuring post types is planned for a future release. Currently, post types can be changed programmatically via the `ppgp_geo_post_types` option.

## The Geo Post Meta Box

When editing a post of an enabled type, you'll see a **Geo Post** meta box in the editor sidebar or below the content area.

### Fields

| Field | Description |
|-------|-------------|
| **Enable geo-data** | Toggle to enable/disable geo data for this post. When disabled, the post won't appear on multi-post maps. |
| **Latitude** | Decimal degrees north/south (e.g., `51.5074` for London). |
| **Longitude** | Decimal degrees east/west (e.g., `-0.1278` for London). Negative values are west of the Prime Meridian. |

### Finding Coordinates

1. Open [Google Maps](https://maps.google.com)
2. Right-click on the location you want
3. Click the coordinates that appear — they'll be copied to your clipboard
4. Paste the latitude and longitude into the respective fields
