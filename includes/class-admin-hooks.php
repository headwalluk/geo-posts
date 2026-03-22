<?php

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

class Admin_Hooks extends Component {

	// public function __construct( string $name, string $version ) {
	// parent::__construct( $name, $version );
	// }

	public function admin_enqueue_scripts( $current_page ) {
		$are_assets_required = false;

		// global $current_page;

		$settings = get_settings_controller();

		// $are_assets_required = $current_page == 'index.php';

		if ( current_user_can( $settings->get_settings_cap() ) ) {
			$are_assets_required |= $current_page == 'settings_page_' . $settings->get_settings_page_name();
		}

		$geo_post_types = $settings->get_array( OPT_GEO_POST_TYPES );
		$this_post_type = get_post_type();
		if ( empty( $this_post_type ) ) {
			$this_post_type = 'post';
		}

		$are_assets_required |= $current_page == 'post.php' && in_array( $this_post_type, $geo_post_types );
		$are_assets_required |= $current_page == 'edit.php' && in_array( $this_post_type, $geo_post_types );
		$are_assets_required |= $current_page == 'post-new.php' && array_key_exists( 'post_type', $_GET ) && in_array( $_GET['post_type'], $geo_post_types );
		$are_assets_required |= $current_page == 'post-new.php' && in_array( $this_post_type, $geo_post_types );

		// error_log( 'Test PP assets: ' . $current_page . ' : ' . $this_post_type );
		if ( $are_assets_required ) {
			// error_log( 'Enqueue PP assets: ' . $current_page );

			wp_enqueue_style( $this->name . '-admin', PPGP_ASSETS_URL . 'admin/pp-admin.css', [], $this->version );
			wp_enqueue_script( $this->name . '-admin', PPGP_ASSETS_URL . 'admin/pp-admin.js', [ 'jquery' ], $this->version, true );
		}
	}

	// public function wp_dashboard_setup() {
	// wp_add_dashboard_widget( 'spamshildstatus', PLUGIN_TITLE, array( $this, 'admin_widget' ) );
	// }

	// public function admin_widget() {
	// include PP_SPS_ADMIN_TEMPLATES_DIR . 'admin-widget.php';
	// }
}
