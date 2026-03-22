<?php

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

class Public_Hooks extends Component {

	public function __construct( string $name, string $version ) {
		parent::__construct( $name, $version );

		$this->has_map_script_been_enqueued = false;
	}

	private $has_map_script_been_enqueued;

	public function enqueue_map_assets() {
		if ( ! $this->has_map_script_been_enqueued ) {
			wp_enqueue_script(
				$this->name, // ...
				PPGP_ASSETS_URL . '/geo-map-posts.js',
				null, // No deps
				$this->version
			);

			wp_localize_script(
				$this->name, // ...
				'wptgmapData',
				array(
					'mapSelector' => '.' . GMAP_CLASS,
				)
			);

			wp_enqueue_style(
				$this->name, // ...
				PPGP_ASSETS_URL . '/geo-map-posts.css',
				null,
				$this->version,
				'all'
			);

			do_action( 'enqueued_geo_map_post_assets' );

			add_action( 'wp_footer', array( $this, 'add_api_key_to_footer' ), 99 );

			$this->has_map_script_been_enqueued = true;
		}
	}

	public function add_api_key_to_footer() {
		$settings = get_settings_controller();
		printf(
			'<script src="https://maps.googleapis.com/maps/api/js?key=%s&libraries=places&&callback=wptgmapInitMaps" async></script>', // ...
			$settings->get_string( OPT_GOOGLE_API_KEY )
		);
	}
}
