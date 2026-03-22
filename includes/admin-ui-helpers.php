<?php
/**
 * Admin UI helper functions.
 *
 * Renders form controls for admin settings and meta box templates.
 * Replaces pp-core's pp_get_text_input_html(), pp_get_checkbox_toggle_html(),
 * and their dependencies.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

/**
 * Get the next unique control ID for form elements.
 *
 * @since 1.0.0
 *
 * @return string Unique control ID.
 */
function ppgp_get_next_control_id(): string {
	static $index = 0;
	++$index;

	return 'ppgp-ctrl-' . $index;
}

/**
 * Render an HTML input element with label and help text.
 *
 * @since 1.0.0
 *
 * @param string $field_name Input name attribute.
 * @param string $label      Label text.
 * @param array  $props      HTML attributes as key => value pairs.
 * @param string $help_text  Optional help text shown below the label.
 *
 * @return string HTML markup.
 */
function ppgp_get_input_html( string $field_name, string $label, array $props = [], string $help_text = '' ): string {
	$control_id = ppgp_get_next_control_id();

	$html = '';

	if ( ! array_key_exists( 'type', $props ) ) {
		$props['type'] = 'text';
	}

	if ( ! empty( $label ) ) {
		$html .= sprintf( '<label for="%s">%s</label>', esc_attr( $control_id ), esc_html( $label ) );
	}

	$html .= sprintf( '<span class="pp-help">%s</span>', esc_html( $help_text ) );

	$html .= sprintf( '<input id="%s" name="%s" ', esc_attr( $control_id ), esc_attr( $field_name ) );

	foreach ( $props as $prop_name => $prop_value ) {
		$html .= sprintf( ' %s="%s"', esc_attr( $prop_name ), esc_attr( $prop_value ) );
	}

	$html .= ' />';

	return $html;
}

/**
 * Render a text input with label and help text.
 *
 * @since 1.0.0
 *
 * @param string $field_name        Input name attribute.
 * @param string $label             Label text.
 * @param string $value             Current value.
 * @param string $help_text         Optional help text.
 * @param string $additional_classes Optional CSS classes.
 *
 * @return string HTML markup.
 */
function ppgp_get_text_input_html( string $field_name, string $label, string $value = '', string $help_text = '', string $additional_classes = '' ): string {
	$props = [
		'type'  => 'text',
		'value' => $value,
	];

	$classes = array_filter( explode( ' ', $additional_classes ) );
	if ( ! empty( $classes ) ) {
		$props['class'] = implode( ' ', $classes );
	}

	return ppgp_get_input_html( $field_name, $label, $props, $help_text );
}

/**
 * Render a checkbox toggle with label.
 *
 * @since 1.0.0
 *
 * @param string $field_name          Input name attribute.
 * @param string $label               Label text.
 * @param bool   $is_checked          Whether the checkbox is checked.
 * @param bool   $has_following_section Whether this toggle controls a following section.
 * @param string $additional_classes  Optional CSS classes.
 *
 * @return string HTML markup.
 */
function ppgp_get_checkbox_toggle_html( string $field_name, string $label, bool $is_checked = false, bool $has_following_section = false, string $additional_classes = '' ): string {
	$control_id = ppgp_get_next_control_id();
	$props      = '';

	if ( $is_checked ) {
		$props .= ' checked';
	}

	$classes = array_filter( explode( ' ', $additional_classes ) );
	$classes[] = 'pp-toggle';

	if ( $has_following_section ) {
		$classes[] = 'cb-section';
	}

	if ( ! empty( $classes ) ) {
		$props .= sprintf( ' class="%s"', esc_attr( trim( implode( ' ', $classes ) ) ) );
	}

	$html = sprintf(
		'<input id="%s" name="%s" type="checkbox" %s/>',
		esc_attr( $control_id ),
		esc_attr( $field_name ),
		$props
	);

	if ( ! empty( $label ) ) {
		$html .= sprintf( '<label for="%s">%s</label>', esc_attr( $control_id ), esc_html( $label ) );
	}

	return $html;
}
