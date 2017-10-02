<?php
/**
 * Template Tags
 *
 * @package   		Geo_Content
 * @subpackage 		template-tags
 * @license   		GPL3
 */

/**
 * LSX GEO Content wrapper to load and manipulate the overall instances.
 *
 * @since 1.0.7
 * @return  \lsx\Geo_Content  A single instance
 */
function lsx_geo_content() {
	// Init geo content class and return object.
	return \lsx\Geo_Content::init();
}

/**
 * Takes the given country code and checks to see if the user is currently located there
 *
 * @param		$country_code string
 * @return		boolean
 *
 * @package 	Geo_Content
 * @subpackage	template-tags
 * @category 	conditional
 */
function lsx_geo_is_country( $country_code = '' ) {
	$geo_content = lsx_geo_content();
	return $geo_content->check_country( $country_code );
}

/**
 * Takes the given key and returns the string from the users location array
 *
 * @param 		$key string
 * @return		string
 *
 * @package 	Geo_Content
 * @subpackage	template-tags
 * @category 	helper
 */
function lsx_geo_get_user_meta( $key ) {
	$geo_content = lsx_geo_content();
	return $geo_content->get_field( $key );
}

/**
 * A shotcode to allow you to display certain content to certain people
 *
 * @param		$atts array
 * @param		$content string
 * @return		string
 *
 * @package 	Geo_Content
 * @subpackage	template-tags
 * @category 	shortcode
 */
function lsx_geo_content_shortcode( $atts, $content = null ) {
	$data = shortcode_atts( array(
		'opening_tag' => '',
		'closing_tag' => '',
		'country' => '',
		'exclude' => '',
	), $atts );

	$countries = explode( '|', $data['country'] );
	$excludes = explode( '|', $data['exclude'] );
	$current_country = lsx_geo_get_user_meta( 'country_code' );

	$return = '';

	if ( in_array( $current_country, $countries ) || ( '' === $countries[0] && ! in_array( $current_country, $excludes ) ) ) {
		$return = $data['opening_tag'] . $content . $data['closing_tag'];
	}

	return $return;
}
add_shortcode( 'geo_content', 'lsx_geo_content_shortcode' );
