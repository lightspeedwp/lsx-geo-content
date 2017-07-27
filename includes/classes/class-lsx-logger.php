<?php

namespace lsx;

if ( ! class_exists( '\lsx\LSX_Logger' ) ) {

	/**
	 * LSX_Logger Main Class
	 *
	 * @package   LSX_Logger
	 * @author    LightSpeed
	 * @license   GPL-3.0+
	 * @link
	 * @copyright 2017 LightSpeedDevelopment
	 */
	class LSX_Logger {

		/**
		 * Holds instance of the class
		 *
		 * @since   1.1.0
		 * @var     \lsx\Geo_Content
		 */
		private static $instance;

		/**
		 * Holds the Logs of what happened
		 */
		private $logs = array();

		/**
		 * Constructor.
		 */
		public function __construct() {

		}

		/**
		 * Return an instance of this class.
		 *
		 * @return  LSX_Logger  A single instance
		 */
		public static function init() {

			// If the single instance hasn't been set, set it now.
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Adds a field to the log
		 *
		 * @param $plugin string
		 * @param $key string
		 * @param $message string
		 * @return  void
		 */
		public function log( $plugin = '', $key = '', $message = '' ) {
			$this->logs[ $plugin ][ $key ] = $message;
		}

		/**
		 * Gets a field from the API lookup object
		 *
		 * @param $plugin string | boolean
		 * @return  string
		 */
		public function output_log( $plugin = false ) {
			$return = '';
			if ( ! empty( $this->logs ) ) {
				foreach ( $this->logs as $plugin_key => $log ) {

					if ( false !== $plugin && $plugin_key !== $plugin ) {
						continue;
					}
					$return = '<div>';
					$this->loop_through_logs( $plugin , $log );
					$return .= '</div>';
				}
			}
			return $return;
		}

		/**
		 * Gets a field from the API lookup object
		 *
		 * @param $plugin string | boolean
		 * @param $log_array array
		 * @return  string
		 */
		public function loop_through_logs( $plugin = false, $log_array = array() ) {
			$return = '';
			foreach ( $log_array as $log_key => $messages ) {
				if ( false === $plugin ) {
					$return .= '<h4>' . $log_key . '</h4>';
				}
				$return .= '<ul>';
				$return .= '<li>' . implode( '</li><li>', $messages ) . '</li>';
				$return .= '</ul>';
			}
			return $return;
		}
	}
}
