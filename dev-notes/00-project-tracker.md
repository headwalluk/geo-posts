# Project Tracker

**Version:** 0.4.0
**Last Updated:** 2026-03-22
**Current Phase:** Milestone 2 (Dead Code & Stub Removal)
**Overall Progress:** ~10%

---

## Overview

Refactor the Geo Posts plugin to bring it up to the current coding standards and project structure
defined in `.github/copilot-instructions.md` and `dev-notes/`. The goals are:

- Remove `pp-core.php` and `pp-assets/` by absorbing pp-core into the plugin's own namespace and
  moving assets into `assets/admin/` and `assets/public/`
- Align file and directory structure with the standards layout
- Eliminate all dead code and unfilled stubs
- Fix all known bugs and inconsistencies
- Add tooling: `composer.json`, `phpcs.xml`
- Make no breaking changes to shortcodes, option keys, meta keys, or hook names used by consumers

---

## Active TODO Items

_Updated as work progresses._

---

## Milestones

### Milestone 1 — Tooling & Standards Setup

**Goal:** Get `phpcs` running and passing on all current code. No logic changes yet.

- [ ] Add `phpcs.xml` with correct prefixes (`ppgp`, `geo_posts`, `Geo_Posts`) and exclusions
      (`pp-core.php`, `pp-assets/`, `assets/`, `dev-notes/`, `vendor/`)
- [ ] Run `phpcs` on all plugin-owned PHP files to baseline current violations
- [ ] Run `phpcbf` to auto-fix formatting violations
- [ ] Manually fix remaining `phpcs` violations (security, naming, doc blocks) in plugin-owned files
- [ ] Confirm `phpcs` passes with zero errors on all plugin-owned files

**Notes:**
- `phpcs` and `phpcbf` are installed globally on the host — no `composer.json` needed
- `pp-core.php` and `pp-assets/` are sealed framework code — exclude from phpcs entirely
- Do not touch `pp-core.php` or `pp-assets/` at this milestone

---

### Milestone 2 — Dead Code & Stub Removal

**Goal:** Delete all dead code, unfilled stubs, and commented-out artifacts from other plugins
so the codebase only contains live, working code.

#### `if (false) {}` dead code blocks

- [ ] Remove `if (false) { ... }` block in `includes/shortcode-multi-post-map.php` (lines 109–226,
      the old taxonomy-filter shortcode path)
- [ ] Remove `if (false) { ... }` block in `assets/geo-map-posts.js` (PlaceID code path)
- [ ] Remove `if (false) { ... }` block in `functions-private.php` (old `wptgmap_enqueue_assets()`
      dead block)

#### Unused constants and definitions

- [ ] Remove unused constants `META_GEO_MODE` and `DEFAULT_GEO_MODE` from `constants.php`
- [ ] Remove or resolve the `PPGP_PUBLIC_TEMPLATES_DIR` constant — either create the `templates/`
      directory (preferred, for forward compatibility) or remove the constant

#### Commented-out code from other plugins

These are copy-paste artifacts from Fancy Product Page, Testimonials, and other plugins:

- [ ] `includes/class-plugin.php` — remove commented-out constructor (lines 14–16), commented-out
      WooCommerce product tabs refs in `init()` (lines 28–35), commented-out testimonial/service
      controller refs in `admin_init()` (lines 40–43, 48–50)
- [ ] `includes/class-admin-hooks.php` — remove commented-out constructor (lines 9–11),
      commented-out `global $current_page`, old page check, `error_log()` debug lines, old
      enqueue calls referencing `PP_GMP_ASSETS_URL` and `ss-admin-settings.*`, and commented-out
      dashboard widget referencing `PLUGIN_TITLE` and `PP_SPS_ADMIN_TEMPLATES_DIR` (lines 16–54)
- [ ] `includes/class-settings.php` — remove commented-out refs to `PP_SMPS_ADMIN_TEMPLATES_DIR`,
      `PP_TTT_ADMIN_TEMPLATES_DIR`, yoast integration, WooCommerce settings, importers, business
      services, socials editor (lines 38–60)
- [ ] `includes/geo-post-meta-box.php` — remove commented-out testimonial_controller,
      service_controller, `PP_FPP_ADMIN_TEMPLATES_DIR` refs (lines 39–51)
- [ ] `admin-templates/geo-post-meta-box.php` — remove commented-out old geo meta approach
      (lines 13–14)
- [ ] `includes/shortcode-single-post-map.php` — remove commented-out PlaceID marker logic
      (lines 80–87)
- [ ] `includes/shortcode-multi-post-map.php` — remove commented-out `apply_filters` placeholder
      (lines 84–85)
- [ ] `assets/geo-map-posts.js` — remove commented-out `console.log()` calls and info window
      HTML template blocks (lines 10, 47–55, 80–85, 100, 124–132)

#### Unused properties, parameters, and functions

- [ ] Remove unused `$socials` and `$configured_socials` properties from
      `includes/class-settings.php` (lines 9–10)
- [ ] Remove unused `place_id` shortcode attribute from `includes/shortcode-single-post-map.php`
      (line 16) — the code that would use it is commented out
- [ ] Remove dead `categories` and `tags` shortcode attributes from
      `includes/shortcode-multi-post-map.php` (lines 22–23, 31–32) — parsed but never used in the
      active query; only referenced inside the `if (false)` dead block
- [ ] Evaluate `get_plugin()` in `functions-private.php` (lines 7–10) — defined but never called;
      remove if not needed or add a usage note

#### Stub comments

- [ ] Clear out `// ...` stub comments in `Plugin::init()`, `Plugin::admin_init()`,
      `Settings::register_settings()`, and `geo-post-meta-box.php` save method — either implement
      or delete

#### Production debug logging

- [ ] Remove active `console.log()` calls in `assets/geo-map-posts.js` (lines 9, 18, 121) —
      these pollute the browser console in production
- [ ] Remove active `error_log()` call in `includes/shortcode-multi-post-map.php` (line 63) —
      should not log to server in production

- [ ] Run `phpcs` and commit

---

### Milestone 3 — Bug Fixes

**Goal:** Fix all known bugs and inconsistencies without changing public-facing behaviour.

#### Critical bugs (user-visible broken functionality)

- [ ] Fix `post_status => 'public'` in `includes/shortcode-multi-post-map.php` (line 52) —
      `'public'` is not a valid WordPress post status; should be `'publish'`. This likely causes
      the multi-post map query to return zero results.
- [ ] Fix hardcoded `'Hello World!'` marker title in `assets/geo-map-posts.js` (line 36) —
      every marker tooltip shows "Hello World!" instead of the actual post title. Should use
      `markerDef.title` or similar.
- [ ] Fix `infowindow` ReferenceError in `assets/geo-map-posts.js` (line 42) — the
      `var infowindow` declaration is trapped inside a `if (false)` dead code block. When info
      windows are enabled, the JS crashes with an undeclared variable. Declare `var infowindow`
      at function scope before the marker loop.

#### Text domain fixes

- [ ] Fix text domain `'fancy-product-page'` → `'geo-posts'` in `includes/geo-post-meta-box.php`
- [ ] Fix text domain `'geo-map-posts'` → `'geo-posts'` in `includes/class-settings.php`
      (lines 18–19, 28, 62)
- [ ] Fix text domain `'geo-map-posts'` → `'geo-posts'` in
      `admin-templates/general-settings.php` (lines 12, 17)
- [ ] Add missing text domain to `__()` call in `admin-templates/general-settings.php` (line 19)
      — currently has no text domain argument at all

#### Security fixes

- [ ] Fix unescaped API key output in `Public_Hooks::add_api_key_to_footer()` — wrap the API key
      value with `esc_attr()` inside the `printf()` URL string
- [ ] Fix double `&&` in Google Maps script URL in `includes/class-public-hooks.php` (line 53) —
      produces a malformed URL (`&&callback=` should be `&callback=`)
- [ ] Sanitize `$_GET['post_type']` in `includes/class-admin-hooks.php` (line 34) — add
      `sanitize_text_field( wp_unslash( ... ) )` before the `in_array()` check
- [ ] Add `wp_unslash()` before `sanitize_text_field()` on `$_POST[ OPT_GOOGLE_API_KEY ]` in
      `includes/class-settings.php` (line 71)
- [ ] Add `wp_unslash()` before `floatval()` on `$_POST[ META_LAT ]` / `$_POST[ META_LON ]` in
      `includes/geo-post-meta-box.php` (lines 64–65)

#### Data consistency fixes

- [ ] Standardise `lon` / `lng` — the PHP shortcodes output `lon` in map data but the JS reads it
      as both `lon` (markers) and `lng` (map centre). Pick `lng` to match the Google Maps JS API
      convention; update PHP shortcodes and `geo-map-posts.js` consistently
- [ ] Fix `[multi_post_map]` shortcode — it hardcodes `post_type => 'post'` and ignores the
      `OPT_GEO_POST_TYPES` option; update the WP_Query to use the saved option value
- [ ] Fix double-slash in asset URLs in `includes/class-public-hooks.php` (lines 21, 36) —
      `PPGP_ASSETS_URL` already has a trailing slash from `trailingslashit()`, so the `/` prefix
      in `'/geo-map-posts.js'` creates `assets//geo-map-posts.js`

- [ ] Run `phpcs` and commit

---

### Milestone 4 — pp-core Integration (Inline & Rename)

**Goal:** Eliminate the separate `pp-core.php` file and `pp-assets/` directory. All pp-core code
and assets are absorbed into the plugin's own structure. This is the core of the refactor.

**Approach:** `pp-core.php` is a sealed, MIT-licensed micro-framework. Rather than rewriting it,
move it as-is into the plugin's `includes/` directory under a plugin-specific filename, and move
`pp-assets/` contents into `assets/admin/` and `assets/public/`. Update all file paths and
enqueue handles to match.

**Sub-tasks:**

#### 4a — Move pp-core.php

- [ ] Rename `pp-core.php` → `includes/pp-core.php` (or `includes/class-pp-core.php` — see note)
- [ ] Update the `require_once` in `geo-posts.php` to point to the new location
- [ ] Confirm no other file references `pp-core.php` by path (grep the codebase)

**Note on naming:** The standards use `class-{name}.php` for files containing classes. Because
`pp-core.php` contains multiple classes and functions, name it `includes/pp-core.php` to keep it
distinguishable from single-class files.

#### 4b — Move pp-assets/ into assets/

- [ ] Create `assets/admin/` directory (already in the standards layout)
- [ ] Create `assets/public/` directory (already in the standards layout)
- [ ] Move `pp-assets/pp-admin.css` → `assets/admin/pp-admin.css`
- [ ] Move `pp-assets/pp-admin.js` → `assets/admin/pp-admin.js`
- [ ] Move `pp-assets/pp-select2.js` → `assets/admin/pp-select2.js`
- [ ] Move `pp-assets/select2.min.css` → `assets/admin/select2.min.css`
- [ ] Move `pp-assets/select2.min.js` → `assets/admin/select2.min.js`
- [ ] Move `pp-assets/pp-public.css` → `assets/public/pp-public.css`
- [ ] Move `pp-assets/pp-public.js` → `assets/public/pp-public.js`
- [ ] Move `pp-assets/spinner.svg` → `assets/admin/spinner.svg`
- [ ] Move `pp-assets/pp-logo.png` → `assets/admin/pp-logo.png`
- [ ] Move `pp-assets/index.php` → `assets/index.php` (directory protection at `assets/` root)
- [ ] Delete the now-empty `pp-assets/` directory

#### 4c — Update pp-core.php path constants

`pp-core.php` defines `PP_BASE_DIR`, `PP_BASE_URL`, `PP_ASSETS_DIR`, `PP_ASSETS_URL` at the top
of the file using `plugin_dir_path( __FILE__ )` and `plugin_dir_url( __FILE__ )`. After moving the
file to `includes/`, these will point into `includes/` rather than the plugin root. Update these
four constants inside `includes/pp-core.php`:

- `PP_BASE_DIR` — should be `plugin_dir_path( __FILE__ ) . '../'` (one level up)
- `PP_BASE_URL` — same adjustment
- `PP_ASSETS_DIR` — should now point to `assets/admin/` (was `pp-assets/`)
- `PP_ASSETS_URL` — same adjustment

- [ ] Update `PP_BASE_DIR` and `PP_BASE_URL` in `includes/pp-core.php`
- [ ] Update `PP_ASSETS_DIR` and `PP_ASSETS_URL` in `includes/pp-core.php` to point to
      `assets/admin/`
- [ ] Verify logo, spinner, and asset enqueue functions resolve correctly after path change
- [ ] Run a full manual admin test: load a geo-enabled post edit screen, confirm pp-admin assets
      load, confirm spinner and logo render

#### 4d — Move geo-specific public assets

- [ ] Move `assets/geo-map-posts.css` → `assets/public/geo-map-posts.css`
- [ ] Move `assets/geo-map-posts.js` → `assets/public/geo-map-posts.js`
- [ ] Update enqueue paths in `Public_Hooks` to reference `assets/public/`
- [ ] Confirm front-end map rendering is unaffected

#### 4e — Cleanup

- [ ] Confirm `pp-assets/` no longer exists
- [ ] Confirm `pp-core.php` no longer exists at the plugin root
- [ ] Run `phpcs` and commit

---

### Milestone 5 — Missing Feature: Post Type Selector UI

**Goal:** Implement the settings page UI for `OPT_GEO_POST_TYPES`. The option already exists and
the meta box already reads it, but there is no way for a user to change it from the admin.

- [ ] Add a post type checkboxes field to `admin-templates/general-settings.php`
      Use `get_post_types( [ 'public' => true ], 'objects' )` to list post types
      Render as checkboxes using `pp_get_admin_checkbox_html()` or similar
- [ ] Update `Settings::save_settings()` to sanitize and save `OPT_GEO_POST_TYPES`
      (array of post type slugs, each sanitized with `sanitize_key()`)
- [ ] Update `Settings::get_default_value()` — already returns `DEFAULT_GEO_POST_TYPES` for this
      option, no change needed
- [ ] Test: change post types in settings, verify meta box appears/disappears on correct post types
- [ ] Run `phpcs` and commit

---

### Milestone 6 — Code Standards Pass (Full)

**Goal:** Apply the full coding standards from `.github/copilot-instructions.md` to all
plugin-owned PHP and JS files. This is a no-logic-change pass focused on style, docs, and patterns.

#### Documentation

- [ ] Add or update PHPDoc blocks on all public methods across all `includes/` classes
- [ ] Ensure `@since` tags are present on all documented methods

#### Structural patterns

- [ ] Ensure all functions follow SESE (Single-Entry Single-Exit) — one `return` per function
- [ ] Fix property declaration ordering: move properties before `__construct()` in
      `class-plugin.php` (lines 56, 65, 74), `class-public-hooks.php` (line 15),
      `class-geo-post.php` (line 21)
- [ ] Replace bitwise OR (`|=`) with logical OR for boolean accumulation in
      `class-admin-hooks.php` (lines 23, 32–35)
- [ ] Refactor assignment-inside-condition patterns in `shortcode-single-post-map.php` (line 27)
      and `geo-post-meta-box.php` (lines 43, 59) into separate assignment + check

#### Templates

- [ ] Ensure all template files (`admin-templates/`, `templates/`) are code-first using `printf()`
      / `echo` — no mixed inline HTML/PHP
- [ ] Replace inline `style=""` attribute in `admin-templates/geo-post-meta-box.php` (line 23)
      with a CSS class
- [ ] Fix typo `pp-checbox` → `pp-checkbox` in `admin-templates/geo-post-meta-box.php` (line 12)

#### Constants and hardcoded values

- [ ] Verify all magic strings and numbers in `includes/` and templates are referenced via
      constants (not hardcoded)
- [ ] Add constant for geo-enabled meta value `'yes'` — used in `class-geo-post.php` (line 32)
      and `shortcode-multi-post-map.php` (line 57)
- [ ] Verify all output is properly escaped (`esc_html()`, `esc_attr()`, `esc_url()`)
- [ ] Verify all input is sanitized on save
- [ ] Check text domains — all translation calls must use `'geo-posts'`
- [ ] Run `phpcs` — zero errors required before committing
- [ ] Commit

---

### Milestone 7 — Geo_Post Extends Post (Optional Refactor)

**Goal:** Reduce duplication by having `Geo_Post` extend pp-core's abstract `Post` class, which
already provides typed meta helpers.

**Note:** Mark as optional because this changes class inheritance, which could theoretically
affect consumers that do `instanceof Geo_Post` checks or call meta methods directly. Evaluate
risk before implementing.

- [ ] Review pp-core's `Post` abstract class meta helpers — confirm they cover `get_bool_meta()`,
      `get_float_meta()`, `get_string_meta()`
- [ ] Decide: extend `Post` or keep `Geo_Post` standalone (document decision here)
- [ ] If extending: update `Geo_Post` to `extends Post`, remove duplicated meta methods
- [ ] Run `phpcs` and commit

---

### Milestone 8 — Final QA & Version Bump

**Goal:** Confirm everything works end-to-end, bump version, and prepare for release.

- [ ] Manual QA checklist:
  - [ ] Settings page loads and saves correctly (API key + post types)
  - [ ] Meta box appears on configured post types, saves lat/lon/enabled correctly
  - [ ] `[single_post_map]` shortcode renders map with correct marker and correct post title
  - [ ] `[multi_post_map]` shortcode renders map with all geo-enabled posts from configured types
  - [ ] Maps load correctly in browser (Google Maps JS API callback fires)
  - [ ] Info windows open without JS errors
  - [ ] Admin assets load on post edit and settings pages (pp-admin.css/js)
  - [ ] No assets load on irrelevant admin pages
  - [ ] No assets load on front-end pages without a map shortcode
  - [ ] No `console.log()` output in browser console
  - [ ] `pp-assets/` directory is gone
  - [ ] `pp-core.php` is gone from plugin root
- [ ] Bump version in `geo-posts.php` plugin header
- [ ] Bump `PPGP_VERSION` constant in `geo-posts.php`
- [ ] Update `PP_CORE_VERSION` / `PP_CORE_DATE` in `includes/pp-core.php` if changed
- [ ] Run `phpcs` — zero errors
- [ ] Commit with `chore: bump version to X.X.X`

---

## Technical Debt

| Item | Location | Priority | Notes |
|---|---|---|---|
| `post_status => 'public'` bug | `shortcode-multi-post-map.php:52` | Critical | Should be `'publish'`; query likely returns zero posts |
| Hardcoded `'Hello World!'` marker title | `geo-map-posts.js:36` | Critical | Every marker tooltip shows wrong text |
| `infowindow` ReferenceError | `geo-map-posts.js:42` | Critical | Variable declared inside dead code; JS crashes when info windows enabled |
| `if (false) {}` dead code | `shortcode-multi-post-map.php` | High | Old taxonomy-filter shortcode path, never completed |
| `if (false) {}` dead code | `assets/geo-map-posts.js` | High | PlaceID code path, never completed |
| `if (false) {}` dead code | `functions-private.php` | High | Old `wptgmap_enqueue_assets()` from prior plugin name |
| Wrong text domain `'fancy-product-page'` | `includes/geo-post-meta-box.php` | High | Should be `'geo-posts'` |
| Wrong text domain `'geo-map-posts'` | `class-settings.php`, `general-settings.php` | High | Should be `'geo-posts'`; one call has no domain at all |
| Unescaped API key output | `includes/class-public-hooks.php` | High | `add_api_key_to_footer()` — wrap key with `esc_attr()` |
| Double `&&` in Maps script URL | `includes/class-public-hooks.php:53` | High | Malformed URL, should be single `&` |
| `lon` / `lng` inconsistency | `shortcode-*.php`, `geo-map-posts.js` | High | PHP outputs `lon`, JS reads both; standardise on `lng` |
| Multi-map ignores post types option | `includes/shortcode-multi-post-map.php` | High | Hardcodes `post_type => 'post'`; should use `OPT_GEO_POST_TYPES` |
| Unsanitized `$_GET['post_type']` | `includes/class-admin-hooks.php:34` | High | Missing `sanitize_text_field( wp_unslash() )` |
| Missing `wp_unslash()` on `$_POST` | `class-settings.php:71`, `geo-post-meta-box.php:64-65` | Medium | WPCS requires `wp_unslash()` before sanitization |
| Active `console.log()` in production JS | `geo-map-posts.js:9, 18, 121` | Medium | Pollutes browser console |
| Active `error_log()` in production PHP | `shortcode-multi-post-map.php:63` | Medium | Should not log in production |
| Commented-out code from other plugins | Multiple files | Medium | Artifacts from Fancy Product Page, Testimonials, etc. |
| Unused properties `$socials`, `$configured_socials` | `class-settings.php:9-10` | Medium | From another plugin |
| Dead `categories`/`tags` shortcode attrs | `shortcode-multi-post-map.php:22-23` | Medium | Parsed but never used in active query |
| Unused constants | `constants.php` | Medium | `META_GEO_MODE`, `DEFAULT_GEO_MODE` — never referenced |
| No post types UI | `admin-templates/general-settings.php` | Medium | `OPT_GEO_POST_TYPES` has no settings page controls |
| Double-slash in asset URLs | `class-public-hooks.php:21, 36` | Low | `PPGP_ASSETS_URL` has trailing `/` + path starts with `/` |
| Typo `pp-checbox` | `admin-templates/geo-post-meta-box.php:12` | Low | Should be `pp-checkbox` |
| Property ordering violations | `class-plugin.php`, `class-public-hooks.php`, `class-geo-post.php` | Low | Properties declared between methods, not at top |
| Hardcoded `'yes'` meta value | `class-geo-post.php:32`, `shortcode-multi-post-map.php:57` | Low | Should be a constant |
| Inline `style=""` in admin template | `admin-templates/geo-post-meta-box.php:23` | Low | Should use CSS class |
| `PPGP_PUBLIC_TEMPLATES_DIR` undefined dir | `geo-posts.php` | Low | Points to non-existent `public-templates/` |
| `Geo_Post` duplicates `Post` meta helpers | `includes/class-geo-post.php` | Low | Does not extend pp-core `Post` |
| `get_plugin()` never called | `functions-private.php:7-10` | Low | Defined but unused |
| No `phpcs.xml` | Plugin root | High | Referenced everywhere but absent |
| No `composer.json` | Plugin root | N/A | Not needed — phpcs/phpcbf installed globally on host |

---

## Notes for Development

### Preserving Backward Compatibility

The following must not change, as consumers (themes, other plugins) may depend on them:

- **Shortcode names:** `[single_post_map]`, `[multi_post_map]` — no renames
- **Shortcode attributes:** `zoom`, `lat`, `lng`, `info`, `caption`, `class` (single map);
  `zoom`, `lat`, `lng`, `info`, `caption`, `class` (multi map)
- **Option keys:** `ppgp_google_api_key`, `ppgp_geo_post_types`
- **Post meta keys:** `geo_lat`, `geo_lon`, `geo_enabled`
- **CSS class on map divs:** `ppgp-map` (used as data selector in `geo-map-posts.js`)
- **JS callback name:** `wptgmapInitMaps` (used in Google Maps API `?callback=` parameter)
- **JS global:** `wptgmapData` (localized via `wp_localize_script`)

### pp-core / pp-assets Are Sealed

`pp-core.php` and `pp-assets/` are MIT-licensed framework files from Power Plugins / Create
Element Ltd. Their internals must not be refactored, reformatted, or subjected to `phpcs`. They
are treated as sealed third-party code. The only permitted changes are:

1. Moving them to new paths (Milestone 4)
2. Updating the four path constants inside `pp-core.php` to reflect the new location (Milestone 4c)

### The `lon` → `lng` Migration

When standardising `lon` → `lng`, the `data-map` JSON attribute written by the shortcodes changes
key names. This is safe because `geo-map-posts.js` is the only consumer of these JSON attributes
and both are updated together. The post meta keys (`geo_lat`, `geo_lon`) do **not** change — only
the JSON keys in the rendered HTML output.
