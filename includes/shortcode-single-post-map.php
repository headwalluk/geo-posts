<?php

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

function do_shortcode_single_post_map( $atts ) {
	$html = '';
	if ( is_admin() || wp_doing_ajax() ) {
		// Don't do anything.
	} else {
		$public_hooks = get_public_hooks();
		$public_hooks->enqueue_map_assets();

		$defaults = array(
			'place_id' => '',
			'zoom'     => DEFAULT_MAP_ZOOM,
			'lat'      => 0.0,
			'lng'      => 0.0,
			'info'     => false,
			'caption'  => '',
			'class'    => '',
		);

		$marker = null;

		if ( ! empty( ( $post_id = get_the_ID() ) ) ) {
			$lat = floatval( get_post_meta( $post_id, META_LAT, true ) );
			$lon = floatval( get_post_meta( $post_id, META_LON, true ) );

			if ( $lat !== 0.0 && $lon !== 0.0 ) {
				$defaults['lat'] = $lat;
				$defaults['lng'] = $lon;

				$marker = array(
					'postId'    => $post_id,
					'lat'       => $lat,
					'lon'       => $lon,
					'title'     => get_the_title( $post_id ),
					'url'       => get_the_permalink( $post_id ),
					'excerpt'   => get_the_excerpt( $post_id ),
					'imageUrls' => array(
						'thumb' => get_the_post_thumbnail_url( $post_id, 'medium' ),
						'full'  => get_the_post_thumbnail_url( $post_id, 'full' ),
					),
				);
			}
		}

		$args = shortcode_atts( $defaults, $atts );

		// Sanitise
		$args['lat'] = floatval( $args['lat'] );
		$args['lng'] = floatval( $args['lng'] );

		$additional_classes = explode( ',', $args['class'] );

		$classes = array_merge( array( 'map', GMAP_CLASS ), $additional_classes );

		$map_data = array(
			'map'             => array(
				'zoom'   => intval( $args['zoom'] ),
				'center' => array(
					'lat' => $args['lat'],
					'lng' => $args['lng'],
				),
			),
			'markers'         => array(),
			'showInfoWindows' => false, // boolval($args['info'])
		);

		if ( ! empty( $marker ) ) {
			$map_data['markers'][] = $marker;
		}

		// If place_id is specified, split it into an array by commas, so we
		// can spcify multiple Google PlaceIDs (for multiple markers).). If there's
		// only one value in place_id then we'll end up with an array with only
		// one element.
		// if (!empty($args['place_id'])) {
		// $place_ids = explode(',', $args['place_id']);
		// foreach ($place_ids as $place_id) {
		// $map_data['markers'][] = [
		// 'placeId' => trim($place_id)
		// ];
		// }
		// }

		// Write our DIV map container.
		$html .= '<figure class="google-map">';
		$html .= sprintf(
			'<div class="%s" data-map="%s"></div>', // ...
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( json_encode( $map_data ) )
		);

		// Optional caption, below the map.
		if ( ! empty( $args['caption'] ) ) {
			$html .= sprintf( '<figcaption>%s</figcaption>', esc_html( $args['caption'] ) );
		}

		$html .= '</figure>';
	}

	return $html;
}
add_shortcode( 'single_post_map', '\\Geo_Posts\\do_shortcode_single_post_map' );
