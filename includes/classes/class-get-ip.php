<?php

namespace lsx;

/**
 * Get_IP Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class Get_IP {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Holds the IP Address of the current user
	 */
	private $ip = false;

	/**
	 * Holds the IP Protocol Version being used
	 */
	private $protocol = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->find_ip();
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
	 * Returns the IP as a variable
	 *
	 * @return string | bool
	 */
	public function get_ip() {
		return $this->ip;
	}

	/**
	 * Returns the protocol version being used, or false if none detected.
	 *
	 * @return string | bool
	 */
	public function get_protocol_version() {
		return $this->protocol;
	}

	/**
	 * Finds the IP of the user from the $_SERVER vars and set the $ip var
	 * @return void
	 */
	public function find_ip() {

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		// Debug Helper for now.
		if ( isset( $_GET['ip'] ) ) {
			$ip = $_GET['ip'];
		}

		// If ip contains commas, take first.
		if ( strpos( $ip, ',' ) !== false ) {
			$ip = explode( ',', $ip );
			$ip = trim( $ip[0] );
		}

		if ( false !== $this->validate_ip( $ip ) ) {
			$this->ip = $ip;
		}
	}

	/**
	 * Tests to see if this is an IP address set the protocol being used
	 *
	 * @param string $ip
	 * @return string
	 */
	public function validate_ip( $ip ) {
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
			$this->protocol = '6';
		} elseif ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$this->protocol = '4';
		} else {
			$this->protocol = false;
		}

		return $ip;
	}
}
