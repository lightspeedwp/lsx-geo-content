<?php

namespace lsx;
use lsx\Get_IP;
use lsx\LSX_Logger;

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
	 * Holds the array of field keys
	 */
	private $fields = array( 'ip', 'country_name', 'country_code', 'region_code', 'region_name', 'city', 'zip_code', 'metro_code', 'time_zone', 'latitude', 'longitude' );

	/**
	 * Holds location to the geoip v4 .dat file
	 */
	private $data4 = LSX_GEO_PATH . 'assets/data/GeoIP.dat';

	/**
	 * Holds location to the geoip v6 .dat file
	 */
	private $data6 = LSX_GEO_PATH . 'assets/data/GeoIPv6.dat';

	/**
	 * Holds open file object
	 */
	private $file_obj = false;

	/**
	 * Holds the IP object
	 */
	private $ip_obj = false;

	/**
	 * If the debug is active
	 */
	private $log_enabled = false;

	/**
	 * Holds the IP object
	 */
	private $logger = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			$this->log_enabled = true;
			$this->logger = \lsx\LSX_Logger::init();
		}
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
		$this->ip_obj = \lsx\Get_IP::init();
		$ip_address = $this->ip_obj->get_ip();
		$response = false;

		if ( ! is_admin() ) {
			//$response = get_transient('lsx_geo_ip_' . $ip_address);

			if ( false === $response ) {

				$db_country_code = $this->check_db_file();

				if ( false !== $db_country_code ) {
					$this->parse_file_response( $db_country_code );
					$this->maybe_log( 'api-lookup', esc_html__( 'Location grabbed from GeoIP.dat', 'lsx-geo-content' ) );
				} else {

					//This will eventually become a setting.
					$service = 'freegeoip';
					if ( isset( $this->apis[ $service ] ) ) {
						$response = wp_safe_remote_get( $this->apis[ $service ] . $ip_address, array(
							'timeout' => 2, ) );
						$this->parse_response( $response );
					}
				}
			} else {
				$this->location_data = $response;
				$this->maybe_log( 'api-lookup', esc_html__( 'Location grabbed from transient', 'lsx-geo-content' ) );
			}
		} else {
			$this->location_data = array();
		}
	}

	/**
	 * Checks the data files for an IP match.
	 *
	 * @return string
	 */
	public function check_db_file() {
		$country_code = false;
		if ( '4' === $this->ip_obj->get_protocol_version() ) {
			$this->file_obj = geoip_open( $this->data4,GEOIP_STANDARD );
			$country_code = geoip_country_code_by_addr( $this->file_obj, $this->ip_obj->get_ip() );
		} else {
			$this->file_obj = geoip_open( $this->data6,GEOIP_STANDARD );
			$country_code = geoip_country_code_by_addr_v6( $this->file_obj, $this->ip_obj->get_ip() );
		}
		return $country_code;
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
				$this->maybe_log( 'api-lookup', esc_html__( 'Location grabbed from API', 'lsx-geo-content' ) . '<pre>' . print_r( $response_decoded, true ) . '</pre>' );
			}
		}
	}

	/**
	 * Validate the response from the Data File
	 *
	 * @param $country_code string
	 * @return void
	 */
	public function parse_file_response( $country_code ) {
		if ( false !== $this->file_obj && false !== $this->ip_obj ) {
			$data = array(
				'ip' => $this->ip_obj->get_ip(),
				'country_code' => $country_code,
			);

			if ( '4' === $this->ip_obj->get_protocol_version() ) {
				$data['country_name'] = geoip_country_name_by_addr( $this->file_obj, $this->ip_obj->get_ip() );
			} else {
				$data['country_name'] = geoip_country_name_by_addr_v6( $this->file_obj, $this->ip_obj->get_ip() );
			}

			$this->location_data = $data;
			set_transient( 'lsx_geo_ip_' . $this->ip_obj->get_ip() , $data , 60 * 60 );
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

	/**
	 * Logs a message with the LSX Logger if WP_Debug is enabled.
	 *
	 * @param $key string
	 * @param $message string
	 * @return void
	 */
	public function maybe_log( $key, $message ) {
		if ( $this->log_enabled ) {
			$this->logger->log( 'lsx-geo-content', $key, $message );
		}
	}
}
