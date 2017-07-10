<?php
/**
 * LSX Geo Content helper functions
 *
 * @package   LSX_Geo_Content
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2017 LightSpeed
 **/

/**
 * LSX GEO Content class autoloader.
 * It locates and finds class via classes folder structure.
 *
 * @param string $class class name to be checked and loaded.
 */
function lsx_geo_content_autoload_class( $class ) {

	$parts = explode( '\\', $class );

	if ( 'lsx' === $parts[0] ) {
		$path = LSX_GEO_PATH . 'includes/classes/';
		array_shift( $parts );
		$name = array_shift( $parts );

		if ( file_exists( $path . $name ) ) {
			$file = str_replace( '_', '-', strtolower( array_pop( $parts ) ) );
			if ( ! empty( $parts ) ) {
				$path .= '/' . implode( '/', $parts );
			}
			$class_file = $path . $name . '/class-' . $file . '.php';
			if ( file_exists( $class_file ) ) {
				include_once $class_file;

				return;
			}
		}
		$name = str_replace( '_', '-', strtolower( $name ) );

		if ( file_exists( LSX_GEO_PATH . 'includes/classes/class-' . $name . '.php' ) ) {
			include_once LSX_GEO_PATH . 'includes/classes/class-' . $name . '.php';
		}
	}

}

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