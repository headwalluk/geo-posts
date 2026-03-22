<?php
/**
 * Core plugin functionality.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

class Plugin extends Component {

	// public function __construct( string $name, string $version ) {
	// parent::__construct( $name, $version );
	// }

	public function run() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		$this->settings = new Settings( $this->name, $this->version );
		add_action( 'admin_menu', array( $this->settings, 'initialise_admin_menu' ) );
	}

	public function init() {
		if ( is_admin() || wp_doing_ajax() ) {
			// ...
		} else {
			// $public_hooks = $this->get_public_hooks();

			// add_action('wp_enqueue_scripts', array($public_hooks, 'wp_enqueue_scripts'));
			// add_filter('get_the_archive_title_prefix', array($public_hooks, 'get_the_archive_title_prefix'), 10, 1);
			// add_filter('woocommerce_product_tabs', array($public_hooks, 'woocommerce_product_tabs'));
		}
	}

	public function admin_init() {
		// Always register all post types when in the back-end of the site.
		// $this->get_testimonial_controller();
		// if ($this->settings->get_bool(OPT_ENABLE_BUSINESS_SERVICES)) {
		// $this->get_service_controller();
		// }

		$admin_hooks = $this->get_admin_hooks();

		add_action( 'admin_enqueue_scripts', array( $admin_hooks, 'admin_enqueue_scripts' ), 10, 1 );
		// add_filter('save_post', array($this, 'sanitise_testimonial'), 15, 3);

		// new Testimonial_Meta_Box();
		new Geo_Post_Meta_Box();

		$this->settings->maybe_save_settings();
	}

	private $admin_hooks;
	public function get_admin_hooks() {
		if ( is_null( $this->admin_hooks ) ) {
			$this->admin_hooks = new Admin_Hooks( $this->name, $this->version );
		}

		return $this->admin_hooks;
	}

	private $public_hooks;
	public function get_public_hooks() {
		if ( is_null( $this->public_hooks ) ) {
			$this->public_hooks = new Public_Hooks( $this->name, $this->version );
		}

		return $this->public_hooks;
	}

	private $settings;
	public function get_settings_controller() {
		return $this->settings;
	}
}
