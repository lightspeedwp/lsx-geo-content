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
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'caldera_forms_render_get_field_type-text', array( $this, 'text_field_placeholder' ) );
		add_filter( 'caldera_forms_render_get_field_type-dropdown', array( $this, 'dropdown_field_placeholder' ) );
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
		$translatable_country = esc_attr__( 'Country', 'lsx-geo-content' );
		if ( $translatable_country === $field['label'] ) {
			$api_obj = \lsx\API_Lookup::init();
			$country_name = $api_obj->get_field( 'country_name' );
			$field['config']['default'] = $country_name;
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
		$translatable_country = esc_attr__( 'Country', 'lsx-geo-content' );
		if ( $translatable_country === $field['label'] ) {
			$api_obj = \lsx\API_Lookup::init();
			$country_name = $api_obj->get_field( 'country_name' );
			$country_code = $api_obj->get_field( 'country_code' );

			$default = false;
			foreach ( $field['config']['option'] as $key => $values ) {
				if ( in_array( $country_name , $values ) || in_array( $country_code , $values ) ) {
					$default = $key;
				}
			}
			if ( false !== $default ) {
				$field['config']['default'] = $default;
			}
		}
		return $field;
	}
}
