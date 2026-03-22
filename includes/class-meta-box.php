<?php
/**
 * Abstract meta box base class.
 *
 * Provides nonce handling, post type filtering, and save-permission checks.
 * Replaces the pp-core Meta_Box class.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

abstract class Meta_Box {

	private string $save_action;
	private string $nonce_field_name;
	private array $post_types;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $post_types       Post types this meta box applies to.
	 * @param string       $save_action      Nonce action name. Auto-generated if empty.
	 * @param string       $nonce_field_name  Nonce field name. Auto-generated if empty.
	 */
	public function __construct( $post_types, string $save_action = '', string $nonce_field_name = '' ) {
		if ( empty( $post_types ) ) {
			$this->post_types = [ 'post' ];
		} elseif ( is_array( $post_types ) ) {
			$this->post_types = $post_types;
		} else {
			$this->post_types = explode( ' ', $post_types );
		}

		$class_slug = sanitize_title( get_class( $this ) );

		$this->save_action      = ! empty( $save_action ) ? $save_action : $class_slug . '_' . PPGP_NAME . '_svembx';
		$this->nonce_field_name = ! empty( $nonce_field_name ) ? $nonce_field_name : $class_slug . '_' . PPGP_NAME . '_nncembx';
	}

	/**
	 * Get the post types this meta box is registered for.
	 *
	 * @since 1.0.0
	 *
	 * @return array Post type slugs.
	 */
	public function get_post_types(): array {
		return $this->post_types;
	}

	/**
	 * Output the nonce field for this meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_nonce_field(): void {
		wp_nonce_field( $this->save_action, $this->nonce_field_name );
	}

	/**
	 * Check whether the current request is a valid meta box save.
	 *
	 * Verifies nonce, post type, capabilities, and autosave status.
	 *
	 * @since 1.0.0
	 *
	 * @param int      $post_id The post ID.
	 * @param \WP_Post $post    The post object.
	 *
	 * @return bool True if the meta box should save.
	 */
	public function is_saving_meta_box( $post_id, $post ): bool {
		$is_saving = false;

		if ( ! array_key_exists( $this->nonce_field_name, $_POST ) ) {
			// No nonce submitted.
		} elseif ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $this->nonce_field_name ] ) ), $this->save_action ) ) {
			// Invalid nonce.
		} elseif ( ! in_array( $post->post_type, $this->post_types, true ) ) {
			// Wrong post type.
		} elseif ( empty( ( $wp_post_type = get_post_type_object( $post->post_type ) ) ) ) {
			// Unknown post type.
		} elseif ( ! current_user_can( $wp_post_type->cap->edit_post, $post_id ) ) {
			// Insufficient permissions.
		} elseif ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			// Autosave — skip.
		} else {
			$is_saving = true;
		}

		return $is_saving;
	}
}
