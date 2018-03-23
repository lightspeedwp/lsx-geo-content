<?php

namespace lsx;

/**
 * WPML_Integration Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class WPML_Integration {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Constructor.
	 */
	public function __construct() {
		apply_filters( 'wpml_current_language', array( $this, 'set_user_location' ), 10, 1 );
	}

	/**
	 * Return an instance of this class.
	 */
	public static function init() {

		// If the single instance hasn't been set, set it now.
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Queries the Geo Lookup APIs and saves the data
	 *
	 * @param $current_country string
	 * @return string
	 */
	public function set_user_location( $current_country = false ) {
		return $current_country;
	}
}
