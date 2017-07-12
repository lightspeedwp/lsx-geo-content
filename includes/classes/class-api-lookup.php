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
	 * Holds current users location data
	 */
	private $location_data = array();

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
		$response = false;

		if ( ! defined( 'WP_DEBUG' ) || false === WP_DEBUG ) {
			$response = get_transient( 'lsx_geo_ip_' . $ip_address );
		}

		if ( false === $response ) {
			//This will eventually become a setting.
			$service = 'freegeoip';
			if ( isset( $this->apis[ $service ] ) ) {
				$response = wp_safe_remote_get( $this->apis[ $service ] . $ip_address, array(
					'timeout' => 2,
				) );

				if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
					$response = array();
					if ( ! isset( $_GET['country'] ) ) {
						$response['body'] = '{"ip":"169.0.145.96","country_code":"ZA","country_name":"South Africa","region_code":"WC","region_name":"Western Cape","city":"Cape Town","zip_code":"7945","time_zone":"Africa/Johannesburg","latitude":-33.9258,"longitude":18.4232,"metro_code":0}';
					} elseif ( 'US' === $_GET['country'] ) {
						$response['body'] = '{"ip":"100.0.145.96","country_code":"US","country_name":"United States","region_code":"MA","region_name":"Massachusetts","city":"Worcester","zip_code":"01609","time_zone":"America/New_York","latitude":42.2857,"longitude":-71.8292,"metro_code":506}';
					}
				}

				$this->parse_response( $response );
			}
		} else {
			$this->location_data = $response;
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
			if ( isset( $response_decoded['ip'] ) ) {
				$this->location_data = $response_decoded;
				set_transient( 'lsx_geo_ip_' . $response_decoded['ip'] , $response_decoded , 60 * 60 );
			}
		}
	}

	/**
	 * Return a field from the location data
	 *
	 * @param $index string
	 * @return mixed
	 */
	public function get_field( $index ) {
		$return = false;
		if ( ! empty( $this->location_data ) && isset( $this->location_data[ $index ] ) ) {
			$return = $this->location_data[ $index ];
		}
		return $return;
	}
}
