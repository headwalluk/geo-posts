<?php
/**
 * Admin area: Geo-post meta box.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

echo '<p class="pp-form-row pp-checbox">';
// $is_enabled = (bool) filter_var( get_post_meta( $post->ID, META_IS_GEO_POST, true ), FILTER_VALIDATE_BOOLEAN );
// echo pp_get_admin_checkbox_html(
echo ppgp_get_checkbox_toggle_html(
	META_IS_GEO_POST, // ...
	__( 'Enable geo-data', 'geo-posts' ),
	$geo_post->get_is_active(),
	true
);
echo '</p>'; // .pp-form-row

printf( '<section style="display:%s;">', $geo_post->get_is_active() ? 'block' : 'none' );

echo '<fieldset class="lat-lon">';
echo '<p class="pp-form-row">';
echo ppgp_get_text_input_html( META_LAT, esc_html__( 'Latitude', 'geo-posts' ), $geo_post->get_latitude(), esc_html__( 'Deg north/south', 'geo-posts' ) );
echo '</p>';

echo '<p class="pp-form-row">';
echo ppgp_get_text_input_html( META_LON, esc_html__( 'Longitude', 'geo-posts' ), $geo_post->get_longitude(), esc_html__( 'Deg east/west', 'geo-posts' ) );
echo '</p>';
echo '</fieldset>';

echo '</section>';
