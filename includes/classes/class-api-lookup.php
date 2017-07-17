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
		'freegeoip'  => '://freegeoip.net/json/',
	);

	/**
	 * Holds the array of field keys
	 */
	private $fields = array( 'ip', 'country_name', 'country_code', 'region_code', 'region_name', 'city', 'zip_code', 'metro_code', 'time_zone', 'latitude', 'longitude' );

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

				$protocol = 'http';
				if ( is_ssl() ) {
					$protocol .= 's';
				}
				$response = file_get_contents( $protocol . $this->apis[ $service ] . $ip_address );
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
		if ( ! is_wp_error( $response ) && '' !== $response ) {
			$response_decoded = json_decode( $response , true );
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

	/**
	 * Returns the array of fields
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}
}
