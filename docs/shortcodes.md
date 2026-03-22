# Shortcode Reference

Geo Posts provides two shortcodes for displaying Google Maps on your site.

## `[single_post_map]`

Displays a map with a marker for the current post. Use this inside post content.

### Attributes

| Attribute | Default | Description |
|-----------|---------|-------------|
| `zoom`    | `5`     | Map zoom level (1-20). Higher numbers zoom in closer. |
| `lat`     | `0`     | Latitude for the map centre. Defaults to the post's latitude if available. |
| `lng`     | `0`     | Longitude for the map centre. Defaults to the post's longitude if available. |
| `info`    | `false` | Show info windows on marker click. |
| `caption` | (empty) | Optional caption displayed below the map. |
| `class`   | (empty) | Additional CSS classes for the map container (comma-separated). |

### Examples

```
[single_post_map]

[single_post_map zoom="12"]

[single_post_map zoom="15" caption="Our office location"]

[single_post_map zoom="10" class="full-width,shadow"]
```

### Notes

- The map automatically centres on the post's coordinates if lat/lon meta is set.
- If the post has no coordinates, the map will show the default centre (0, 0) unless overridden via attributes.
- The post marker includes the post title, URL, excerpt, and featured image data.

---

## `[multi_post_map]`

Displays a map with markers for all geo-enabled posts. Use this on any page or post.

### Attributes

| Attribute | Default | Description |
|-----------|---------|-------------|
| `zoom`    | `5`     | Map zoom level (1-20). |
| `lat`     | `54`    | Latitude for the map centre. Default centres on the UK. |
| `lon`     | `-3`    | Longitude for the map centre. |
| `info`    | `false` | Show info windows on marker click. |
| `caption` | (empty) | Optional caption displayed below the map. |
| `class`   | (empty) | Additional CSS classes for the map container (comma-separated). |

### Examples

```
[multi_post_map]

[multi_post_map zoom="6" lat="51.5" lon="-0.1" caption="Our locations across London"]

[multi_post_map zoom="4" lat="54" lon="-3" info="true"]
```

### Notes

- Only posts with "Enable geo-data" ticked and valid (non-zero) coordinates will appear as markers.
- Each marker includes the post title, URL, excerpt, and featured image.

---

## Styling

Both shortcodes render the map inside a `<figure class="google-map">` element. The map `<div>` itself has the CSS class `ppgp-map` plus any additional classes you specify.

To control map dimensions, target the `.ppgp-map` class in your theme CSS:

```css
.ppgp-map {
    width: 100%;
    height: 400px;
}
```
