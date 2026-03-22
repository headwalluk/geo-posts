<?php

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

function do_shortcode_multi_post_map( $atts ) {
	$html = '';
	if ( is_admin() || wp_doing_ajax() ) {
		// Don't do anything.
	} else {
		$public_hooks = get_public_hooks();
		$public_hooks->enqueue_map_assets();

		$defaults = array(
			'zoom'       => DEFAULT_MAP_ZOOM,
			'lat'        => DEFAULT_MAP_LAT,
			'lon'        => DEFAULT_MAP_LON,
			'info'       => false,
			'caption'    => '',
			'class'      => '',
			'categories' => '',
			'tags'       => '',
		);

		$args = shortcode_atts( $defaults, $atts );

		// Sanitise
		$args['lat']        = floatval( $args['lat'] );
		$args['lon']        = floatval( $args['lon'] );
		$args['categories'] = explode( ',', $args['categories'] );
		$args['tags']       = explode( ',', $args['tags'] );

		$additional_classes = explode( ',', $args['class'] );

		$classes = array_merge( array( 'map', GMAP_CLASS ), $additional_classes );

		$map_data = array(
			'map'             => array(
				'zoom'   => intval( $args['zoom'] ),
				'center' => array(
					'lat' => $args['lat'],
					'lng' => $args['lon'],
				),
			),
			'markers'         => array(),
			'showInfoWindows' => true, // false //boolval($args['info'])
		);

		$query = array(
			'post_type'   => 'post',
			'post_status' => 'public',
			'meta_query'  => array(
				array(
					'key'     => META_IS_GEO_POST,
					'compare' => '=',
					'value'   => 'yes',
				),
			),
		);

		if ( ! is_array( $posts = get_posts( $query ) ) ) {
			error_log( __FUNCTION__ . ' No location posts' );
		} else {
			foreach ( $posts as $post ) {
				$post_id = $post->ID;
				$lat     = floatval( get_post_meta( $post_id, META_LAT, true ) );
				$lon     = floatval( get_post_meta( $post_id, META_LON, true ) );

				if ( $lat !== 0.0 && $lon !== 0.0 ) {
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

					// filter the marker meta...
					// apply_filters( ... );

					if ( ! empty( $marker ) ) {
						$map_data['markers'][] = $marker;
					}
				}
			}
		}

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

		if ( false ) {
			$defaults = array(
				'place_id'   => '',
				'zoom'       => DEFAULT_MAP_ZOOM,
				'lat'        => 0.0,
				'lng'        => 0.0,
				'info'       => false,
				'caption'    => '',
				'class'      => '',
				'categories' => '',
				'tags'       => '',
			);

			$args = shortcode_atts( $defaults, $atts );

			// Sanitise
			$args['lat']        = floatval( $args['lat'] );
			$args['lng']        = floatval( $args['lng'] );
			$args['categories'] = explode( ',', $args['categories'] );
			$args['tags']       = explode( ',', $args['tags'] );

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
				'showInfoWindows' => true, // false //boolval($args['info'])
			);

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

			$post_ids = array();

			$tax_query = array();

			if ( ! empty( $args['categories'] ) ) {
				$tax_query[] = array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $args['categories'],
					'operator' => 'IN',
				);
			}

			if ( ! empty( $args['categories'] ) ) {
				$tax_query[] = array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => $args['tags'],
					'operator' => 'IN',
				);
			}

			if ( count( $tax_query ) > 1 ) {
				$tax_query['relation'] = 'AND';
			}

			if ( ! empty( $tax_query ) ) {
				$query = array(
					'post_type'   => 'post',
					'post_status' => 'publish',
					'fields'      => 'ids',
					'numberposts' => -1,
					'tax_query'   => $tax_query,
				);

				if ( ! is_array( $post_ids = get_posts( $query ) ) ) {
					// ...
				} else {
					foreach ( $post_ids as $post_id ) {
						$lat = floatval( get_post_meta( $post_id, META_LAT, true ) );
						$lon = floatval( get_post_meta( $post_id, META_LON, true ) );

						if ( $lat !== 0.0 && $lon !== 0.0 ) {
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

							// filter the marker meta...
							// apply_filters( ... );

							if ( ! empty( $marker ) ) {
								$map_data['markers'][] = $marker;
							}
						}
					}
				}
				// error_log('posts: ' . count($posts));
			}
		}
	}

	return $html;
}
add_shortcode( 'multi_post_map', '\\Geo_Posts\\do_shortcode_multi_post_map' );
