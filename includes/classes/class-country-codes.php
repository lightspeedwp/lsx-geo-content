<?php

namespace lsx;

/**
 * Country_Codes Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class Country_Codes {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Holds current users location data
	 */
	private $data;

	/**
	 * Holds the array of Geo IP sites and the urls
	 */
	private $apis = array(
		'countryio'  => 'http://country.io/names.json',
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->lookup();
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
	 * Queries the Country Data APIs and saves the data
	 *
	 * @return void
	 */
	public function lookup() {

		$response = get_option( 'lsx_geo_ip_country_data' , false );

		if ( false === $response ) {
			//This will eventually become a setting.
			$service = 'countryio';
			if ( isset( $this->apis[ $service ] ) ) {
				$response = wp_safe_remote_get( $this->apis[ $service ] , array(
					'timeout' => 2,
				) );
				$this->parse_response( $response );
			}
		} else {
			$this->data = $response;
		}
	}

	/**
	 * Validate the response from the API
	 *
	 * @param $response string
	 * @return void
	 */
	public function parse_response( $response ) {
		if ( ! is_wp_error( $response ) && $response['body'] ) {
			$response_decoded = json_decode( $response['body'] , true );
			if ( false !== $response_decoded && '' !== $response_decoded ) {
				asort( $response_decoded );
				$this->data = $response_decoded;
				add_option( 'lsx_geo_ip_country_data' , $response_decoded );
			}
		}
	}
}
