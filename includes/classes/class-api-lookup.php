<?php

namespace lsx;
use lsx\Get_IP;

/**
 * API_Lookup Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class API_Lookup {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Holds the array of Geo IP sites and the urls
	 */
	private $apis = array(
		'freegeoip'  => 'https://freegeoip.net/json/',
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
	 * Queries the Geo Lookup APIs and saves the data
	 *
	 * @return void
	 */
	public function lookup() {
		$ip_obj = \lsx\Get_IP::init();
		$ip_address = $ip_obj->get_ip();

		//This will eventually become a setting.
		$service = 'freegeoip';
		if ( isset( $this->apis[ $service ] ) ) {
			$response = wp_safe_remote_get( $this->apis[ $service ] . $ip_address, array( 'timeout' => 2 ) );

			$response = array();
			$response['body'] = '{"ip":"169.0.145.96","country_code":"ZA","country_name":"South Africa","region_code":"WC","region_name":"Western Cape","city":"Cape Town","zip_code":"7945","time_zone":"Africa/Johannesburg","latitude":-33.9258,"longitude":18.4232,"metro_code":0}';

			$this->parse_response( $response );
		}
	}

	/**
	 * Validate the response from the API
	 *
	 * @param $response string
	 * @return void
	 */
	public function parse_response( $response ) {
		if ( ! is_wp_error( $response ) && $response[ 'body' ] ) {
			$response_decoded = json_decode( $response[ 'body' ] );
			if ( isset( $response_decoded->ip ) ) {
				set_transient( 'lsx_geo_ip_' . $response_decoded->ip , 60 * 60 );
			}
		}
	}
}
