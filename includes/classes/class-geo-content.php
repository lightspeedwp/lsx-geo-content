<?php

namespace lsx;

use lsx\API_Lookup;

/**
 * Geo_Content Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class Geo_Content {

	/**
	 * Holds instance of the class
	 *
	 * @since   1.1.0
	 * @var     \lsx\Geo_Content
	 */
	private static $instance;

	/**
	 * Holds the current users country
	 */
	private $country;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'locate_user' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.1.0
	 * @return  Geo_Content  A single instance
	 */
	public static function init() {

		// If the single instance hasn't been set, set it now.
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Grabs the Users IP Address and Checks the APIs for the location
	 *
	 * @return  void
	 */
	public function locate_user() {
		\lsx\API_Lookup::init();
	}

	/**
	 * @return mixed
	 */
	public function get_country() {
		return $this->country;
	}
}
