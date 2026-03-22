# CLAUDE.md — Geo Posts WordPress Plugin

## Project Overview

Geo Posts is a WordPress plugin (GPLv3) that adds latitude/longitude metadata to posts
and renders Google Maps via shortcodes.

- **Version:** 0.4.0
- **Namespace:** `Geo_Posts`
- **Text domain:** `geo-posts`
- **PHP requirement:** 8.0+
- **No build pipeline** — no package.json, webpack, or TypeScript. JS files are vanilla
  ES5/ES6 served directly.
- **No autoloader** — all files are manually `require_once`'d in `geo-posts.php`.
- **No composer.json** — phpcs and phpcbf are installed globally on the host system.

## Build / Lint / Test Commands

There is no test suite (no phpunit.xml, no tests/ directory).

### PHPCS (linting)

PHPCS and WPCS are installed globally on this system. A `phpcs.xml` exists in the plugin
root with prefixes and exclusions configured.

```bash
# Lint all files
phpcs

# Lint a single file
phpcs includes/class-plugin.php

# Lint a directory
phpcs includes/

# Auto-fix formatting violations
phpcbf

# Auto-fix a single file
phpcbf includes/class-plugin.php
```

### Pre-commit workflow

```bash
phpcs && phpcbf && phpcs    # check, auto-fix, verify
git add . && git commit
```

### WP-CLI

WP-CLI is installed on the host system. Use it for plugin activation, option checks, etc.

```bash
wp plugin activate geo-posts
wp option get ppgp_google_api_key
```

## Documentation Structure

This project has two separate documentation directories:

- **`docs/`** — User-facing documentation for website owners, designers, and developers
  who want to use or extend the plugin. This is linked from README.md and published on
  GitHub. Content: installation, shortcode reference, settings, API setup.

- **`dev-notes/`** — Internal development documentation for contributors working on the
  codebase. Not for end users. Content: project tracker, refactor milestones, coding
  patterns, workflow guides, tech debt inventory.

Do not mix these up. User docs go in `docs/`. Dev/refactor notes go in `dev-notes/`.

## Archived Files

The `archived/` directory contains the original pp-core framework files for reference:

- `archived/pp-core.php` — Original PP-Core micro-framework (MIT licensed)
- `archived/pp-assets/` — Original PP-Core CSS/JS/images

These were absorbed into the plugin's own classes in v0.4.0. The archived copies are
kept for reference only — do not modify or reference them from active code.

## File Structure

```
geo-posts/
├── geo-posts.php             # Main plugin entry point
├── constants.php             # All constants (namespace Geo_Posts)
├── functions-private.php     # Internal helper functions
├── phpcs.xml                 # PHPCS configuration
├── README.md                 # GitHub readme (lean, links to docs/)
├── readme.txt                # WordPress.org readme
├── CHANGELOG.md              # Version history
├── includes/                 # Core PHP classes
│   ├── class-component.php       # Base class (name + version)
│   ├── class-settings-core.php   # Abstract settings base
│   ├── class-meta-box.php        # Abstract meta box base
│   ├── admin-ui-helpers.php      # Form rendering helpers (ppgp_ prefix)
│   ├── class-plugin.php          # Main plugin orchestrator
│   ├── class-settings.php        # Settings page
│   ├── class-admin-hooks.php     # Admin hook handlers
│   ├── class-public-hooks.php    # Front-end hook handlers
│   ├── class-geo-post.php        # Geo post data wrapper
│   ├── geo-post-meta-box.php     # Geo meta box registration and save
│   ├── shortcode-single-post-map.php  # [single_post_map]
│   └── shortcode-multi-post-map.php   # [multi_post_map]
├── admin-templates/          # Admin-side PHP templates
├── assets/
│   ├── admin/                # Admin CSS/JS (pp-admin.css, pp-admin.js)
│   ├── geo-map-posts.css     # Public map styles
│   └── geo-map-posts.js      # Public map JavaScript
├── docs/                     # User-facing documentation
├── dev-notes/                # Internal dev documentation and project tracker
├── archived/                 # Original pp-core files (reference only)
└── .github/copilot-instructions.md  # Full coding standards
```

## Code Style Guidelines

The canonical reference is `.github/copilot-instructions.md` (v1.6.0). Key rules below.

### PHP Formatting (WordPress Coding Standards)

- **Indentation:** Tabs, not spaces.
- **Spaces inside parentheses:** `if ( $condition )`, `array( 'key' => $val )`.
- **Short array syntax allowed:** `[ 'a', 'b' ]` (WPCS exclusion configured).
- **No `declare(strict_types=1)`** — WordPress doesn't use it; causes interop bugs.

### File Conventions

- **Every PHP file starts with:** `defined( 'ABSPATH' ) || die();`
- **Class files:** `class-{name}.php` (lowercase, hyphenated). One class per file.
- **Namespace:** `namespace Geo_Posts;` in all files except `geo-posts.php`.
- **Constants file:** All magic strings/numbers go in `constants.php`.

### Naming Conventions

- **Constants:** Prefixed by purpose — `OPT_` (wp_options keys), `DEF_`/`DEFAULT_`
  (default values), `META_` (post meta keys), `GMAP_` (map-related).
- **Global functions:** Prefixed `ppgp_` (plugin slug).
- **Classes:** PascalCase with underscores: `Admin_Hooks`, `Geo_Post`, `Public_Hooks`.
- **Methods/functions:** snake_case: `get_geo_posts()`, `add_api_key_to_footer()`.
- **CSS classes for JS selectors:** Use class selectors (`.ppgp-map`), not IDs.

### Class Organization

Order within a class:
1. Properties (public, then protected, then private)
2. `__construct()`
3. Public methods
4. Protected methods
5. Private methods

### Function Pattern — Single-Entry Single-Exit (SESE)

Functions should have **one return statement at the end**. Build up a `$result` variable
and return it once, rather than using multiple early returns.

```php
function get_value(): string {
    $result = 'default';
    if ( condition_a() ) {
        $result = 'value_a';
    }
    return $result;
}
```

### Type Hints

Use PHP 8.0+ type hints and return types on all new functions. Nullable types (`?string`)
and union types (`int|false`) are fine.

### PHPDoc Blocks

Required on all public methods. Format:

```php
/**
 * Brief description.
 *
 * @since 1.0.0
 *
 * @param string $param Description.
 * @return string Description.
 */
```

### Templates — No Inline HTML

All template files must use code-first rendering with `printf()` / `echo`. Never mix
inline HTML with PHP open/close tags. This prevents whitespace bugs.

### Boolean Options

Always use `filter_var()` when reading boolean options:

```php
$enabled = (bool) filter_var( get_option( OPT_ENABLED, false ), FILTER_VALIDATE_BOOLEAN );
```

### Date/Time

Store as human-readable strings (`Y-m-d H:i:s T`), not Unix timestamps.

### Security

- **Sanitize all input:** `absint()`, `sanitize_text_field()`, `sanitize_email()`, etc.
- **Escape all output:** `esc_html()`, `esc_attr()`, `esc_url()`.
- **Verify nonces** on all form submissions and AJAX handlers.
- **Check capabilities** before performing privileged operations.
- **Prepare database queries** with `$wpdb->prepare()`.

### Error Handling

Use `WP_Error` for recoverable errors. Check with `is_wp_error()`.

### JavaScript

- No inline `<script>` tags — enqueue via `wp_enqueue_script()`.
- Use class-based selectors (`.plugin-calendar`), not IDs.
- Pass data from PHP via `wp_localize_script()`.

## Git Conventions

### Commit Message Format

```
type: brief description

- Detail 1
- Detail 2
```

**Types:** `feat:` `fix:` `chore:` `refactor:` `docs:` `style:` `test:`

### Pre-commit Checklist

1. Run `phpcs` — fix all violations
2. Run `phpcbf` for auto-fixable issues
3. Verify with `phpcs` again
4. Review `git diff` before committing

## Backward Compatibility — Do Not Rename

These public-facing identifiers must not change:

- **Shortcodes:** `[single_post_map]`, `[multi_post_map]`
- **Option keys:** `ppgp_google_api_key`, `ppgp_geo_post_types`
- **Post meta keys:** `geo_lat`, `geo_lon`, `geo_enabled`
- **CSS class:** `ppgp-map`
- **JS callback:** `wptgmapInitMaps`
- **JS global:** `wptgmapData`

## Known Issues (Tech Debt)

See `dev-notes/00-project-tracker.md` for the full list. High-priority items:

- Wrong text domain `'fancy-product-page'` in `includes/geo-post-meta-box.php`
- Unescaped API key in `Public_Hooks::add_api_key_to_footer()`
- `lon`/`lng` inconsistency between PHP shortcodes and JS
- `[multi_post_map]` hardcodes `post_type => 'post'` instead of using `OPT_GEO_POST_TYPES`
- Multiple `if (false) {}` dead code blocks across PHP and JS files
- Commented-out code from other plugins (Fancy Product Page, Testimonials)

## Further Reading

- `.github/copilot-instructions.md` — Full 579-line coding standards (canonical)
- `dev-notes/00-project-tracker.md` — Refactor milestones and tech debt inventory
- `dev-notes/workflows/code-standards.md` — PHPCS setup guide
- `dev-notes/workflows/commit-to-git.md` — Git workflow guide
- `dev-notes/patterns/` — Reference patterns (caching, settings, templates, etc.)
