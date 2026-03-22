<?php
/**
 * Base component class.
 *
 * Provides name and version properties to all plugin components.
 * Replaces the pp-core Component class.
 *
 * @package GeoPosts
 */

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

class Component {

	protected string $name;
	protected string $version;

	public function __construct( string $name, string $version ) {
		$this->name    = $name;
		$this->version = $version;
	}
}
