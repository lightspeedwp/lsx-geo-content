<?php

namespace lsx;

use lsx\API_Lookup;

/**
 * CF_Geo_Filters Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class CF_Geo_Filters {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Holds instance of the api lookup class
	 */
	private $api_obj = false;

	/**
	 * Holds the array of classes available
	 */
	private $classes = array( 'lsx-geo-ip', 'lsx-geo-country', 'lsx-geo-region', 'lsx-geo-zip-code', 'lsx-geo-metro-code', 'lsx-geo-city', 'lsx-geo-latitude', 'lsx-geo-longitude' );

	/**
	 * Holds current key to display
	 */
	private $current_key = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'caldera_forms_render_get_field_type-text', array( $this, 'text_field_placeholder' ) );
		add_filter( 'caldera_forms_render_get_field_type-hidden', array( $this, 'text_field_placeholder' ) );
		add_filter( 'caldera_forms_render_get_field_type-dropdown', array( $this, 'dropdown_field_placeholder' ) );
		$this->api_obj = \lsx\API_Lookup::init();
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
	 * Filter the text fields looking for the country field to filter
	 *
	 * @param $field array
	 * @return array
	 */
	public function text_field_placeholder( $field ) {
		if ( '' !== $field['config']['custom_class'] && $this->check_classes( $field['config']['custom_class'] ) ) {
			if ( 'country' === $this->current_key || 'region' === $this->current_key ) {
				$this->current_key .= '_name';
			}
			$default = $this->api_obj->get_field( $this->current_key );
			$field['config']['default'] = $default;
		}

		return $field;
	}

	/**
	 * Filter the dropdown fields looking for the country field to filter
	 *
	 * @param $field array
	 * @return array
	 */
	public function dropdown_field_placeholder( $field ) {
		if ( '' !== $field['config']['custom_class'] && $this->check_classes( $field['config']['custom_class'] ) ) {

			$needles = array();

			switch ( $this->current_key ) {
				case 'country':
				case 'region':
					$needles[] = $this->api_obj->get_field( $this->current_key . '_name' );
					$needles[] = $this->api_obj->get_field( $this->current_key . '_code' );
					break;

				case 'city':
				case 'latitude':
				case 'longitude':
				case 'metro-code':
				case 'zip-code':
					$needles[] = $this->api_obj->get_field( $this->current_key . '_name' );
					break;

				default:
					break;
			}

			foreach ( $field['config']['option'] as $key => $values ) {
				if ( ! empty( array_intersect( $needles, $values ) ) ) {
					$field['config']['default'] = $key;
				}
			}
		}
		return $field;
	}

	/**
	 * Checks the classes from the form field for the lsx-geo-filters
	 *
	 * @param $field_classes string
	 * @return boolean
	 */
	public function check_classes( $field_classes ) {
		$this->current_key = false;
		foreach ( $this->classes as $class ) {
			if ( stristr( $field_classes, $class ) ) {
				$this->current_key = str_replace( 'lsx-geo-', '', $class );
				$this->current_key = str_replace( '-', '_', $this->current_key );
				return true;
			}
		}
		return false;
	}
}
