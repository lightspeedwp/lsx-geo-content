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
class LSX_Currencies_Integration {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'lsx_currencies_base_currency', array( $this, 'set_user_location' ),10 , 2 );
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
	 * @param $currenciesOBJ object
	 * @return string
	 */
	public function set_user_location( $current_country = false, $currencies_obj ) {
		$geo_content = lsx_geo_content();
		$flag_relations = $currencies_obj->flag_relations;
		$flag_relations = array_flip( $flag_relations );

		$country_code = strtolower( $geo_content->get_field( 'country_code' ) );
		if ( false !== $country_code && '' !== $country_code && isset( $flag_relations[ $country_code ] ) ) {
			$current_country = $flag_relations[ $country_code ];
		}
		return $current_country;
	}
}
