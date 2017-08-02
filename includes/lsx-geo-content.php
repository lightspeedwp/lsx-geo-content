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

/* ======================= The API Classes ========================= */

if(!class_exists('LSX_API_Manager')){
	require_once('classes/class-lsx-api-manager.php');
}

/**
 * Runs once when the plugin is activated.
 */
function lsx_geo_content_activate_plugin() {
	$lsx_to_password = get_option('lsx_api_instance',false);
	if(false === $lsx_to_password){
		update_option('lsx_api_instance',LSX_API_Manager::generatePassword());
	}
}
register_activation_hook( __FILE__, 'lsx_geo_content_activate_plugin' );

/**
 *	Grabs the email and api key from the LSX Search Settings.
 */
function lsx_geo_content_options_pages_filter($pages){
	$pages[] = 'lsx-settings';
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter('lsx_api_manager_options_pages','lsx_geo_content_options_pages_filter',10,1);

function lsx_geo_content_api_admin_init(){
	global $lsx_banners_api_manager;

	if(function_exists( 'tour_operator' )) {
		$options = get_option('_lsx-to_settings', false);
	}else{
		$options = get_option('_lsx_settings', false);
		if (false === $options) {
			$options = get_option('_lsx_lsx-settings', false);
		}
	}

	$data = array('api_key'=>'','email'=>'');

	if(false !== $options && isset($options['api'])){
		if(isset($options['api']['lsx-geo-content_api_key']) && '' !== $options['api']['lsx-geo-content_api_key']){
			$data['api_key'] = $options['api']['lsx-geo-content_api_key'];
		}
		if(isset($options['api']['lsx-geo-content_email']) && '' !== $options['api']['lsx-geo-content_email']){
			$data['email'] = $options['api']['lsx-geo-content_email'];
		}
	}

	$instance = get_option( 'lsx_api_instance', false );
	if(false === $instance){
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id'	=>		'LSX Geo Content',
		'version'		=>		'1.0.0',
		'instance'		=>		$instance,
		'email'			=>		$data['email'],
		'api_key'		=>		$data['api_key'],
		'file'			=>		'lsx-geo-content.php'
	);
	$lsx_geo_content_api_manager = new LSX_API_Manager($api_array);
}
add_action('admin_init','lsx_geo_content_api_admin_init');
