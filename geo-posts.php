<?php
/**
 * Plugin Name:       Geo Posts
 * Plugin URI:        https://headwall-hosting.com
 * Description:       Add lat/lon to posts and inject maps
 * Version:           0.4.0
 * Author:            Paul Faulkner
 * Author URI:        https://headwall-hosting.com
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       geo-posts
 * Domain Path:       /languages
 *
 * @package GeoPosts
 */

defined( 'ABSPATH' ) || die();

const PPGP_NAME    = 'geo-posts';
const PPGP_VERSION = '0.4.0';

define( 'PPGP_DIR', plugin_dir_path( __FILE__ ) );
define( 'PPGP_URL', plugin_dir_url( __FILE__ ) );
define( 'PPGP_ADMIN_TEMPLATES_DIR', trailingslashit( PPGP_DIR . 'admin-templates' ) );
define( 'PPGP_PUBLIC_TEMPLATES_DIR', trailingslashit( PPGP_DIR . 'public-templates' ) );
define( 'PPGP_ASSETS_DIR', trailingslashit( PPGP_DIR . 'assets' ) );
define( 'PPGP_ASSETS_URL', trailingslashit( PPGP_URL . 'assets' ) );

require_once PPGP_DIR . 'constants.php';
require_once PPGP_DIR . 'functions-private.php';
require_once PPGP_DIR . 'includes/class-component.php';
require_once PPGP_DIR . 'includes/class-settings-core.php';
require_once PPGP_DIR . 'includes/class-meta-box.php';
require_once PPGP_DIR . 'includes/admin-ui-helpers.php';
require_once PPGP_DIR . 'includes/class-settings.php';
require_once PPGP_DIR . 'includes/class-admin-hooks.php';
require_once PPGP_DIR . 'includes/class-public-hooks.php';

require_once PPGP_DIR . 'includes/class-geo-post.php';

require_once PPGP_DIR . 'includes/geo-post-meta-box.php';

require_once PPGP_DIR . 'includes/class-plugin.php';

require_once PPGP_DIR . 'includes/shortcode-single-post-map.php';
require_once PPGP_DIR . 'includes/shortcode-multi-post-map.php';

function ppgp_plugin_run() {
	global $ppgp_plugin;

	$ppgp_plugin = new Geo_Posts\Plugin( PPGP_NAME, PPGP_VERSION );
	$ppgp_plugin->run();
}
ppgp_plugin_run();
