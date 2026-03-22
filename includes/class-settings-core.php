<?php
/**
 * Abstract settings base class.
 *
 * Provides settings page scaffolding, nonce handling, and typed option
 * getters/setters. Replaces the pp-core Settings_Core class.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

abstract class Settings_Core extends Component {

	private string $settings_action;
	private string $settings_nonce;
	protected string $settings_cap;
	protected string $settings_page_name;

	public function __construct( string $name, string $version ) {
		parent::__construct( $name, $version );

		$this->settings_action    = 'svestngsact' . $name;
		$this->settings_nonce     = 'svestngsnce' . $name;
		$this->settings_cap       = 'manage_options';
		$this->settings_page_name = $name;
	}

	/**
	 * Save settings — implemented by the concrete Settings class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function save_settings();

	/**
	 * Get the capability required to manage settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string Capability name.
	 */
	public function get_settings_cap(): string {
		return $this->settings_cap;
	}

	/**
	 * Get the settings page slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page slug.
	 */
	public function get_settings_page_name(): string {
		return $this->settings_page_name;
	}

	/**
	 * Render the settings page title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $page_title Optional page title override.
	 *
	 * @return void
	 */
	public function render_page_title( string $page_title = '' ): void {
		if ( empty( $page_title ) ) {
			$page_title = get_admin_page_title();
		}

		printf( '<h1>%s</h1>', esc_html( $page_title ) );
	}

	/**
	 * Open the settings page wrapper div.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_wrap(): void {
		echo '<div class="wrap">';
	}

	/**
	 * Open the settings form and output nonce fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function open_form(): void {
		echo '<form method="post">';
		wp_nonce_field( $this->settings_action, $this->settings_nonce );
	}

	/**
	 * Close the settings form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_form(): void {
		echo '</form>';
	}

	/**
	 * Close the settings page wrapper div.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function close_wrap(): void {
		echo '</div>';
	}

	/**
	 * Check whether the current request is a settings save, and if so, call save_settings().
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function maybe_save_settings(): void {
		if ( ! is_admin() || wp_doing_ajax() ) {
			// Not an admin page load.
		} elseif ( ! array_key_exists( $this->settings_nonce, $_POST ) ) {
			// No nonce submitted.
		} elseif ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $this->settings_nonce ] ) ), $this->settings_action ) ) {
			// Invalid nonce.
		} elseif ( ! current_user_can( $this->settings_cap ) ) {
			// Insufficient permissions.
		} else {
			$this->save_settings();
		}
	}

	/**
	 * Get the default value for an option. Override in subclass.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name The option key.
	 *
	 * @return mixed Default value or null.
	 */
	public function get_default_value( string $option_name ) {
		return null;
	}

	/**
	 * Get a string option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name Option key.
	 * @param string $default     Default value.
	 *
	 * @return string Option value.
	 */
	public function get_string( string $option_name, string $default = '' ): string {
		if ( empty( $default ) ) {
			$default = strval( $this->get_default_value( $option_name ) );
		}

		return strval( get_option( $option_name, $default ) );
	}

	/**
	 * Set a string option.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $option_name Option key.
	 * @param string      $value       Option value.
	 * @param string|bool $autoload    Whether to autoload.
	 *
	 * @return void
	 */
	public function set_string( string $option_name, string $value = '', $autoload = null ): void {
		if ( ! empty( $value ) ) {
			update_option( $option_name, $value, $autoload );
		} else {
			delete_option( $option_name );
		}
	}

	/**
	 * Get an array option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_name Option key.
	 * @param array  $default     Default value.
	 *
	 * @return array Option value.
	 */
	public function get_array( string $option_name, array $default = [] ): array {
		if ( empty( $default ) ) {
			$default = $this->get_default_value( $option_name );
		}

		if ( ! is_array( $default ) ) {
			$default = [];
		}

		return (array) get_option( $option_name, $default );
	}

	/**
	 * Set an array option.
	 *
	 * @since 1.0.0
	 *
	 * @param string      $option_name Option key.
	 * @param array       $value       Option value.
	 * @param string|bool $autoload    Whether to autoload.
	 *
	 * @return void
	 */
	public function set_array( string $option_name, array $value = [], $autoload = null ): void {
		if ( ! empty( $value ) ) {
			update_option( $option_name, $value, $autoload );
		} else {
			delete_option( $option_name );
		}
	}
}
