<?php
/*
 * Plugin Name: LSX Geo Content
 * Plugin URI: https://www.lsdev.biz/product/lsx-geo-content/
 * Description:
 * Tags: lsx , geo content
 * Author: LightSpeed
 * Version: 1.0.0
 * Author URI: https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-geo-content
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_GEO_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_GEO_CORE', __FILE__ );
define( 'LSX_GEO_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_GEO_VER', '1.0.0' );

if ( ! class_exists( 'GeoIP' ) ) {
	require_once LSX_GEO_PATH . 'vendor/geoip/geoip.php';
}

// Include the classes
$classes = array(
	'country-codes',
	'lsx-logger',
	'api-lookup',
	'get-ip',
	'cf-geo-filters',
	'geo-nav-filters',
	'geo-settings',
	'wpml-integration',
	'geo-content',
);
foreach ( $classes as $class ) {
	require_once( LSX_GEO_PATH . 'includes/classes/class-' . $class . '.php' );
}

// include the template tags
require_once( LSX_GEO_PATH . 'includes/template-tags.php' );

// Init Plugin.
lsx_geo_content();


/* ======================= The API Classes ========================= */

/*if(!class_exists('LSX_API_Manager')){
	require_once('classes/class-lsx-api-manager.php');
}*/

/**
 * Runs once when the plugin is activated.
 */
function lsx_geo_content_activate_plugin() {
	$lsx_to_password = get_option('lsx_api_instance',false);
	if(false === $lsx_to_password){
		update_option('lsx_api_instance',LSX_API_Manager::generatePassword());
	}
}
//register_activation_hook( __FILE__, 'lsx_geo_content_activate_plugin' );

/**
 *	Grabs the email and api key from the LSX Search Settings.
 */
function lsx_geo_content_options_pages_filter($pages){
	$pages[] = 'lsx-settings';
	$pages[] = 'lsx-to-settings';
	return $pages;
}
//add_filter('lsx_api_manager_options_pages','lsx_geo_content_options_pages_filter',10,1);

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
//add_action('admin_init','lsx_geo_content_api_admin_init');
