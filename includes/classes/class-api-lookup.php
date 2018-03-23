<?php

namespace lsx;
use lsx\Get_IP;
use lsx\LSX_Logger;
use lsx\Country_Codes;

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
	 * Holds the logger object
	 */
	private $logger = false;

	/**
	 * If 1 API request has happened
	 */
	private $have_requested_before = false;

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
		$response = false;

		if ( ! is_admin() ) {
			$response = get_transient( 'lsx_geo_ip_' . $this->ip_obj->get_ip() );

			if ( false === $response ) {

				if ( $this->check_cloudfare() ) {
					return;
				}

				$db_country_code = $this->check_db_file();

				if ( false !== $db_country_code ) {
					$this->parse_file_response( $db_country_code );
				} else {
					$this->contact_api();
				}
			} else {
				$this->location_data = $response;
				$this->maybe_log( 'transient', esc_html__( 'Location grabbed from ', 'lsx-geo-content' ) );
			}
			$this->maybe_log( 'location result', '<pre>' . print_r( $this->location_data, true ) . '</pre>' );
		} else {
			$this->location_data = array();
		}
	}

	/**
	 * contacts the API
	 *
	 * @return boolean
	 */
	public function check_cloudfare() {
		// This will eventually become a setting.
		if ( ! empty( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) {
			$country_code = sanitize_text_field( strtoupper( $_SERVER['HTTP_CF_IPCOUNTRY'] ) );
			$this->parse_file_response( $country_code, 'cloudfare' );
		} else {
			return false;
		}
	}

	/**
	 * contacts the API
	 *
	 * @return void
	 */
	public function contact_api() {
		// This will eventually become a setting.
		$service = 'freegeoip';
		if ( false === $this->have_requested_before && isset( $this->apis[ $service ] ) ) {
			$args = array(
				'timeout' => 2,
			);
			$response = wp_safe_remote_get( $this->apis[ $service ] . $this->ip_obj->get_ip(), $args );
			$this->parse_response( $response );
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
			$this->file_obj = geoip_open( $this->data4, GEOIP_STANDARD );
			$country_code = geoip_country_code_by_addr( $this->file_obj, $this->ip_obj->get_ip() );
			$this->maybe_log( 'file-check', esc_html__( 'Checking IP v4', 'lsx-geo-content' ) );
			geoip_close( $this->file_obj );
		} else {
			$this->file_obj = geoip_open( $this->data6, GEOIP_STANDARD );
			$country_code = geoip_country_code_by_addr_v6( $this->file_obj, $this->ip_obj->get_ip() );
			$this->maybe_log( 'file-check', esc_html__( 'Checking IP v6', 'lsx-geo-content' ) );
			geoip_close( $this->file_obj );
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
			$response_decoded = json_decode( $response, true );
			if ( isset( $response_decoded['ip'] ) ) {
				$this->location_data = $response_decoded;
				set_transient( 'lsx_geo_ip_' . $response_decoded['ip'], $response_decoded, 60 * 60 );
				$this->have_requested_before = true;
				$this->maybe_log( 'api-lookup', esc_html__( 'Location from API Request', 'lsx-geo-content' ) . '<pre>' . print_r( $response_decoded, true ) . '</pre>' );
			}
		} else {
			$this->maybe_log( 'api-lookup', esc_html__( 'Error occurred with the API Request', 'lsx-geo-content' ) . '<pre>' . print_r( $response, true ) . '</pre>' );
		}
	}

	/**
	 * Validate the response from the Data File
	 *
	 * @param $country_code string
	 * @param $log_key string
	 * @return void
	 */
	public function parse_file_response( $country_code, $log_key = 'file-search' ) {
		if ( false !== $this->ip_obj ) {
			$data = array(
				'ip' => $this->ip_obj->get_ip(),
				'country_code' => $country_code,
				'country_name' => \lsx\Country_Codes::get_country_name( $country_code ),
			);
			$this->location_data = $data;
			set_transient( 'lsx_geo_ip_' . $this->ip_obj->get_ip(), $data, 60 * 60 );
			$this->maybe_log( $log_key, esc_html__( 'Location from ', 'lsx-geo-content' ) );
		} else {
			$this->maybe_log( $log_key, esc_html__( 'Search failed ', 'lsx-geo-content' ) );
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
