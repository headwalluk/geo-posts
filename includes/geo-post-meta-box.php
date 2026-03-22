<?php
/**
 * Each post has its own option geo-meta.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

class Geo_Post_Meta_Box extends Meta_Box {

	public function __construct() {
		$settings = get_settings_controller();

		parent::__construct( $settings->get_array( OPT_GEO_POST_TYPES ) );

		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	public function register_meta_box() {
		add_meta_box(
			get_class( $this ), // Unique ID
			__( 'Geo Post', 'fancy-product-page' ),
			array( $this, 'render' ),
			$this->get_post_types()
		);
	}

	public function render( $post ) {
		echo '<div class="pp-wrap">';

		$this->render_nonce_field();

		$settings = get_settings_controller();

		// $testimonial_controller = get_testimonial_controller();
		// $service_controller = get_service_controller();
		// $testimonial = $testimonial_controller->get_post_object($post->ID);

		if ( ! empty( ( $geo_post = get_geo_post( $post->ID ) ) ) ) {
			include PPGP_ADMIN_TEMPLATES_DIR . 'geo-post-meta-box.php';
		}

		// error_log( json_encode( $post ) );
		// $geo_post = get_geo_post( $post->ID );
		// include PPGP_ADMIN_TEMPLATES_DIR . 'geo-post-meta-box.php';

		// include PP_FPP_ADMIN_TEMPLATES_DIR . 'product-meta-box.php';

		echo '</div>';
	}

	public function save( $post_id, $post ) {
		if ( ! $this->is_saving_meta_box( $post_id, $post ) ) {
			// ...
		} elseif ( empty( ( $geo_post = get_geo_post( $post_id ) ) ) ) {
			// ...
		} else {
			$geo_post->set_is_active( array_key_exists( META_IS_GEO_POST, $_POST ) );

			$geo_post->set_latitude( array_key_exists( META_LAT, $_POST ) ? floatval( $_POST[ META_LAT ] ) : 0.0 );
			$geo_post->set_longitude( array_key_exists( META_LON, $_POST ) ? floatval( $_POST[ META_LON ] ) : 0.0 );
		}
	}
}
