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
