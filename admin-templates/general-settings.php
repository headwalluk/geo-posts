<?php
/**
 * Admin area: General settings
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

printf( '<h2>%s</h2>', esc_html__( 'General settings', 'geo-map-posts' ) );

echo '<p class="pp-form-row">';
echo ppgp_get_text_input_html(
	OPT_GOOGLE_API_KEY,
	__( 'Your Google API key', 'geo-map-posts' ),
	$settings->get_string( OPT_GOOGLE_API_KEY ),
	__( 'This should have permission to access the Maps Javascript and Places (New) APIs' ),
	'widefat'
);
echo '</p>'; // .form-row
