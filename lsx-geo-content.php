<?php
/*
 * Plugin Name: LSX Geo Content
 * Plugin URI: https://www.lsdev.biz/product/lsx-geo-content/
 * Description:
 * Tags: LSX, LSX Theme, caldera forms, geo content, geo localization
 * Author: LightSpeed
 * Version: 1.0.1
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
define( 'LSX_GEO_VER', '1.0.1' );

if ( ! class_exists( 'GeoIP' ) ) {
	require_once LSX_GEO_PATH . 'vendor/geoip/geoip.php';
}

// Include the classes.
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
	'lsx-currencies-integration',
);

foreach ( $classes as $class ) {
	require_once( LSX_GEO_PATH . 'includes/classes/class-' . $class . '.php' );
}

// include the template tags.
require_once( LSX_GEO_PATH . 'includes/template-tags.php' );

// Init Plugin.
lsx_geo_content();

