<?php
/**
 * Wrapper for manipulating post meta data.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

/**
 * The underlying post type can be any valid WP post type.
 */
class Geo_Post {

	public function __construct( int $post_id ) {
		$this->post_id = $post_id;
	}

	private $post_id;
	public function get_id(): int {
		return $this->post_id;
	}

	public function get_is_active(): bool {
		return (bool) filter_var( get_post_meta( $this->get_id(), META_IS_GEO_POST, true ), FILTER_VALIDATE_BOOLEAN );
	}

	public function set_is_active( bool $is_active ) {
		if ( $is_active ) {
			update_post_meta( $this->get_id(), META_IS_GEO_POST, 'yes' );
		} else {
			delete_post_meta( $this->get_id(), META_IS_GEO_POST );
		}
	}

	public function get_latitude(): float {
		return floatval( get_post_meta( $this->get_id(), META_LAT, true ) );
	}

	public function set_latitude( float $value ) {
		update_post_meta( $this->get_id(), META_LAT, $value );
	}

	public function get_longitude(): float {
		return floatval( get_post_meta( $this->get_id(), META_LON, true ) );
	}

	public function set_longitude( float $value ) {
		update_post_meta( $this->get_id(), META_LON, $value );
	}
}
