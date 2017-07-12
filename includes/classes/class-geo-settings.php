<?php

namespace lsx;

/**
 * Geo_Settings Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class Geo_Settings {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'lsx_to_framework_settings_tabs', array( $this, 'settings_page_array' ) , 100 );
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
	 * Returns the array of settings to the UIX Class in the lsx framework
	 */
	public function settings_page_array( $tabs ) {
		$tabs['geo_content'] = array(
			'page_title'        => false,
			'page_description'  => false,
			'menu_title'        => __( 'Geo Content' ,'lsx-geo-content' ),
			'template'          => LSX_GEO_PATH . 'includes/partials/geo-content.php',
			'default'	 		=> false,
		);
		return $tabs;
	}
}
