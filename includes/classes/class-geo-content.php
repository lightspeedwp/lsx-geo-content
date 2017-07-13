<?php

namespace lsx;


use lsx\API_Lookup;
use lsx\Country_Codes;
use lsx\WPML_Integration;
use lsx\Geo_Settings;
use lsx\CF_Geo_Filters;

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
	 * Holds api_lookup instance with all the location data
	 */
	private $api_lookup = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'locate_user' ) );

		//Settings
		\lsx\Geo_Settings::init();

		//Load the variables
		\lsx\Country_Codes::init();

		//WPML Integration
		\lsx\WPML_Integration::init();

		//Caldera Forms Integration
		\lsx\CF_Geo_Filters::init();

		add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );
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
		$this->api_lookup = \lsx\API_Lookup::init();
	}

	/**
	 * Check if the current users country code against the one supplied.
	 *
	 * @param $country_code string
	 * @return boolean
	 */
	public function check_country( $country_code = '' ) {
		$country = '';
		if ( false !== $this->api_lookup ) {
			$country = $this->api_lookup->get_field( 'country_code' );
		}

		if ( '' !== $country_code && $country_code === $country ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Adds an info box to the "My Account" dropdown
	 *
	 * @return void
	 */
	public function admin_bar_info_box() {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'parent' => 'my-account', // use 'false' for a root menu, or pass the ID of the parent menu
			'id' => 'user-geo-location', // link ID, defaults to a sanitized title value
			'title' => esc_attr__( 'Geo Location', 'lsx-geo-content' ), // link title
			'href' => '', // name of file
			'meta' => array(
				'html' => '<span>Hello</span>',
				),
		));
	}
}
