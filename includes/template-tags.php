<?php
/**
 * Template Tags
 *
 * @package   		Geo_Content
 * @subpackage 		template-tags
 * @license   		GPL3
 */

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
		'country' => 'all',
	), $atts );

	$return = '';
	if ( 'all' === $data['country'] || lsx_geo_is_country( $data['country'] ) ){
		$return = $data['opening_tag'] . $content . $data['closing_tag'];
	}
	return $return;
}
add_shortcode( 'geo_content', 'lsx_geo_content_shortcode' );
