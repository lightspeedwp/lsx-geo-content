<?php

namespace lsx;

/**
 * Geo_Nav_Filters Main Class
 *
 * @package   Geo_Content
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */
class Geo_Nav_Filters {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Holds current menu
	 */
	private $menu;

	/**
	 * Holds the matches of the string search
	 */
	private $matches = array();

	/**
	 * A boolean var enabled if there is a
	 */
	private $has_geo_content = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'wp_nav_menu_objects', array( $this, 'filter_nav_menu' ), 20, 2 );
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
	 * Moves the current languages phone number to the top of the menu
	 *
	 * @param array $sorted_menu_items
	 * @param array	$args
	 *
	 * @return array
	 */
	public function filter_nav_menu( $sorted_menu_items, $args ) {
		$this->menu = $sorted_menu_items;
		foreach ( $this->menu as $menu_key => $menu_item ) {
			if ( in_array( 'lsx-geo', $menu_item->classes ) ) {
				if ( $this->check_items( $menu_item->classes, "/lsx-geo-ex-(.*)/" ) ) {
					$this->exclude_menu_item( $menu_key );
				} else if ( $this->check_items( $menu_item->classes, "/lsx-geo-(.*)/" ) ) {

				}
			}
		}

		$sorted_menu_items = $this->menu;
		return $sorted_menu_items;
	}

	/**
	 * Queries the Country Data APIs and saves the data
	 *
	 * @param array $classes
	 * @param string $pattern
	 * @return boolean
	 */
	public function check_items( $classes , $pattern ) {
		$return = false;
		foreach ( $classes as $class ) {
			$return = $this->search_string( $class, $pattern );
			if ( true === $return ) {
				return $return;
			}
		}
		return $return;
	}

	/**
	 * Does the actual string search
	 *
	 * @param array $string
	 * @param string $pattern
	 * @return boolean
	 */
	private function search_string( $string , $pattern ) {
		$return = false;
		preg_match($pattern, $string, $matches);
		if ( ! empty( $matches ) ) {
			$this->matches = $matches;
			$return = true;
		}
		return $return;
	}

	/**
	 * Removes a menu item from showing
	 *
	 * @param string $key
	 * @return void
	 */
	private function exclude_menu_item( $key ) {
		unset( $this->menu[ $key ] );
	}
}
