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
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'caldera_forms_render_get_field_type-text', array( $this, 'text_field_placeholder' ) );
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
	 * @return string
	 */
	public function text_field_placeholder( $field ) {

		if ( '' !== $field['config']['custom_class'] && in_array( $field['config']['custom_class'] , $this->classes ) ) {
			$key = str_replace( 'lsx-geo-', '', $field['config']['custom_class'] );
			$key = str_replace( '-', '_', $key );
			if ( 'country' === $key || 'region' === $key ) {
				$key .= '_name';
			}
			$default = $this->api_obj->get_field( $key );
			$field['config']['default'] = $default;
		}

		return $field;
	}

	/**
	 * Filter the dropdown fields looking for the country field to filter
	 *
	 * @param $field array
	 * @return string
	 */
	public function dropdown_field_placeholder( $field ) {

		if ( '' !== $field['config']['custom_class'] && in_array( $field['config']['custom_class'] , $this->classes ) ) {

			$needles = array();
			$index_key = str_replace( 'lsx-geo-', '', $field['config']['custom_class'] );

			switch ( $field['config']['custom_class'] ) {
				case 'lsx-geo-country':
				case 'lsx-geo-region':
					$needles[] = $this->api_obj->get_field( $index_key . '_name' );
					$needles[] = $this->api_obj->get_field( $index_key . '_code' );
					break;

				case 'lsx-geo-city':
				case 'lsx-geo-latitude':
				case 'lsx-geo-longitude':
				case 'lsx-geo-metro-code':
				case 'lsx-geo-postal-code':
					$index_key = str_replace( '-', '_', $index_key );
					$needles[] = $this->api_obj->get_field( $index_key . '_name' );
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
}
