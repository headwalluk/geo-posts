<?php

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

function get_plugin() {
	global $ppgp_plugin;
	return $ppgp_plugin;
}

function get_settings_controller() {
	global $ppgp_plugin;
	return $ppgp_plugin->get_settings_controller();
}

function get_public_hooks() {
	global $ppgp_plugin;
	return $ppgp_plugin->get_public_hooks();
}

if ( false ) {
	function wptgmap_enqueue_assets() {
		global $wptgmap_have_assets_been_enqueued;
		// Make sure we don't enqueueour assets more than once, even if there
		// are multiple GMap shortcodes on the page.
		if ( is_null( $wptgmap_have_assets_been_enqueued ) ) {
			$base_url = get_stylesheet_directory_uri();
			$version  = wp_get_theme()->get( 'Version' );
			// Enqueue our styles.
			wp_enqueue_style( 'wptgmap', $base_url . '/wpt-google-map/wpt-google-maps.css', null, $version, 'all' );
			// Enqueue our frontend JavaScript and set the wptgmapData to hold
			// our global data.
			wp_enqueue_script( 'wptgmap', $base_url . '/wpt-google-map/wpt-google-maps.js', null, $version );
			wp_localize_script(
				'wptgmap',
				'wptgmapData',
				array(
					'mapSelector' => '.' . WPTGMAP_CLASS,
				)
			);
			// Enqueue the Google Maps JS in the footer.
			add_action( 'wp_footer', 'wptgmap_page_footer', 99 );
			$wptgmap_have_assets_been_enqueued = true;
		}
	}
}

function get_geo_post( int $post_id ): ?Geo_Post {
	$geo_post = null;

	if ( ! empty( get_post_status( $post_id ) ) ) {
		$geo_post = new Geo_Post( $post_id );
	}

	return $geo_post;
}
