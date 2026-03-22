<?php

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

class Settings extends Settings_Core {

	private $socials;
	private $configured_socials;

	public function __construct( string $name, string $version ) {
		parent::__construct( $name, $version );
	}

	public function initialise_admin_menu() {
		add_options_page(
			__( 'Geo Posts', 'geo-map-posts' ), // ...
			__( 'Geo Posts', 'geo-map-posts' ),
			$this->settings_cap,
			$this->get_settings_page_name(),
			array( $this, 'render_settings_page' )
		);
	}

	public function render_settings_page() {
		if ( ! current_user_can( $this->settings_cap ) ) {
			printf( '<p>%s</p>', esc_html__( 'Not authorized', 'geo-map-posts' ) );
		} else {
			$this->open_wrap();

			$this->render_page_title();

			$this->open_form();

			$settings = $this;

			// include PP_SMPS_ADMIN_TEMPLATES_DIR . 'general-settings.php';

			// if (IS_YOAST_INTEGRATION_ENABLED && is_yoast_active()) {
			// include PP_SMPS_ADMIN_TEMPLATES_DIR . 'yoast-settings.php';
			// }

			include PPGP_ADMIN_TEMPLATES_DIR . 'general-settings.php';

			echo '<hr />';

			// include PP_SMPS_ADMIN_TEMPLATES_DIR . 'socials-editor.php';

			// if ((bool) apply_filters('ttt_is_importer_enabled', IS_IMPORTER_ENABLED)) {
			// include PP_TTT_ADMIN_TEMPLATES_DIR . 'settings-import.php';
			// }

			// if ((bool) apply_filters('ttt_is_business_service_enabled', IS_BUSINESS_SERVICE_ENABLED)) {
			// include PP_TTT_ADMIN_TEMPLATES_DIR . 'settings-business-service.php';
			// }

			// if (is_woocommerce_available()) {
			// include PP_TTT_ADMIN_TEMPLATES_DIR . 'settings-woocommerce.php';
			// }

			submit_button( esc_html__( 'Save Changes', 'geo-map-posts' ) );

			$this->close_form();

			$this->close_wrap();
		}
	}

	public function save_settings() {
		$this->set_string( OPT_GOOGLE_API_KEY, array_key_exists( OPT_GOOGLE_API_KEY, $_POST ) ? sanitize_text_field( $_POST[ OPT_GOOGLE_API_KEY ] ) : '' );
		// ...
	}

	public function get_default_value( string $option_name ) {
		$value = null;

		switch ( $option_name ) {
			case OPT_GEO_POST_TYPES:
				$value = DEFAULT_GEO_POST_TYPES;
				break;

			default:
				// ...
				break;
		}

		return $value;
	}
}
