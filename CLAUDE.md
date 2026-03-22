# AGENTS.md — Geo Posts WordPress Plugin

## Project Overview

Geo Posts is a WordPress plugin (GPLv3) that adds latitude/longitude metadata to posts
and renders Google Maps via shortcodes. It uses a sealed MIT-licensed micro-framework
called PP-Core (`pp-core.php` + `pp-assets/`).

- **Namespace:** `Geo_Posts`
- **Text domain:** `geo-posts`
- **PHP requirement:** 8.0+
- **No build pipeline** — no package.json, webpack, or TypeScript. JS files are vanilla
  ES5/ES6 served directly.
- **No autoloader** — all files are manually `require_once`'d in `geo-posts.php`.

## Build / Lint / Test Commands

There is no test suite (no phpunit.xml, no tests/ directory). No composer.json — phpcs
and phpcbf are installed globally on the host system.

### PHPCS (linting)

PHPCS and WPCS are installed globally on this system. A `phpcs.xml` does not yet exist in
the plugin root (planned for Milestone 1). When it does, these commands apply:

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

## Sealed / Do-Not-Modify Files

These are third-party framework files. **Never modify, reformat, or run phpcs on them:**

- `pp-core.php` — PP-Core micro-framework (MIT, multiple classes)
- `pp-assets/` — PP-Core CSS/JS/images

## File Structure

```
geo-posts/
├── geo-posts.php             # Main plugin entry point
├── constants.php             # All constants (namespace Geo_Posts)
├── functions-private.php     # Internal helper functions
├── includes/                 # Core PHP classes (class-*.php)
├── admin-templates/          # Admin-side PHP templates
├── assets/                   # Public-facing CSS/JS
├── pp-core.php               # Sealed framework (DO NOT EDIT)
├── pp-assets/                # Sealed framework assets (DO NOT EDIT)
├── dev-notes/                # Dev documentation and project tracker
└── .github/copilot-instructions.md  # Full 579-line coding standards
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
- No `phpcs.xml` yet

## Further Reading

- `.github/copilot-instructions.md` — Full 579-line coding standards (canonical)
- `dev-notes/00-project-tracker.md` — Refactor milestones and tech debt inventory
- `dev-notes/workflows/code-standards.md` — PHPCS setup guide
- `dev-notes/workflows/commit-to-git.md` — Git workflow guide
- `dev-notes/patterns/` — Reference patterns (caching, settings, templates, etc.)
