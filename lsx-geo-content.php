<?php
/*
 * Plugin Name: LSX GEO Content
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

// include context helper & autoloader.
require_once( LSX_GEO_PATH . 'includes/lsx-geo-content.php' );

// include the template tags
require_once( LSX_GEO_PATH . 'includes/template-tags.php' );

// Register tour operator autoloader.
spl_autoload_register( 'lsx_geo_content_autoload_class', true, false );

// Init Plugin.
lsx_geo_content();

// Register activation hook.
register_activation_hook( LSX_GEO_CORE, array(
	'LSX_Geo_Content',
	'register_activation_hook',
) );
