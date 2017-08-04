<?php

namespace lsx;

use lsx\API_Lookup;

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
	 * Holds the matches of the preg_match statement
	 */
	private $matches = array();

	/**
	 * Holds the current menu items matched
	 */
	private $matched_countries = array();

	/**
	 * The users current country code
	 */
	private $user_country_code = false;

	/**
	 * Holds the current menus default item
	 */
	private $default = array();

	/**
	 * Holds the menu item that should display for this country
	 */
	private $selected = array();

	/**
	 * Holds the current menus parent item
	 */
	private $parent = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'wp_nav_menu_objects', array( $this, 'filter_nav_menu' ), 20, 2 );
		$api_lookup = \lsx\API_Lookup::init();
		$this->user_country_code = strtolower( $api_lookup->get_field( 'country_code' ) );
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
	 * Return an instance of this class.
	 */
	public function reset_variables() {
		$this->default = array();
		$this->selected = array();
		$this->parent = array();
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
		if ( ! empty( $this->menu ) ) {
			$this->loop_through_menu_items();
			$this->set_new_parent();
			$this->reset_variables();
		}
		$sorted_menu_items = $this->menu;
		return $sorted_menu_items;
	}

	/**
	 * Run through the menu items and check for the ".lsx-geo" class,  then take certain actions base don those classes.
	 *
	 * @return void
	 */
	private function loop_through_menu_items() {
		foreach ( $this->menu as $menu_key => $menu_item ) {
			if ( in_array( 'lsx-geo', $menu_item->classes ) ) {
				$this->matches = array();

				if ( $this->check_items( $menu_item->classes, '/lsx-geo-parent/' ) ) {
					$this->set_current_parent( $menu_key, $menu_item );
				} else if ( $this->check_items( $menu_item->classes, '/lsx-geo-default/' ) ) {
					$this->set_default_item( $menu_key, $menu_item );
				} else if ( $this->check_items( $menu_item->classes, '/lsx-geo-ex-(.*)/' ) ) {
					$this->exclude_menu_item( $menu_key );
				} else if ( $this->check_items( $menu_item->classes, '/lsx-geo-(.*)/' ) ) {
					$this->check_for_selection( $menu_key, $menu_item );
				}
			}
		}
	}

	/**
	 * Queries the Country Data APIs and saves the data
	 *
	 * @param array $classes
	 * @param string $pattern
	 * @return boolean
	 */
	public function check_items( $classes, $pattern ) {
		$return = false;
		$matched_countries = array();
		foreach ( $classes as $class ) {
			$search = $this->search_string( $class, $pattern );

			if ( true === $search ) {
				if ( isset( $this->matches[1] ) ) {
					$matched_countries[] = $this->matches[1];
				}
				$return = $search;
			}
		}
		$this->matched_countries = $matched_countries;
		return $return;
	}

	/**
	 * Does the actual string search
	 *
	 * @param array $string
	 * @param string $pattern
	 * @return boolean
	 */
	private function search_string( $string, $pattern ) {
		$return = false;
		preg_match( $pattern, $string, $matches );
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
		if ( ! empty( $this->matched_countries ) && in_array( $this->user_country_code, $this->matched_countries ) ) {
			unset( $this->menu[ $key ] );
		}
	}

	/**
	 * Check an item to see if it should replace the parent
	 *
	 * @param string $key
	 * @param array $item
	 * @return void
	 */
	private function check_for_selection( $key, $item ) {
		if ( ! empty( $this->matched_countries ) && in_array( $this->user_country_code, $this->matched_countries ) ) {
			$this->selected['key'] = $key;
			$this->selected['obj'] = $item;
			$this->exclude_menu_item( $key );
		}
	}

	/**
	 * Sets the current parent menu item
	 *
	 * @param string $key
	 * @param array $item
	 * @return void
	 */
	private function set_current_parent( $key, $item ) {
		$this->parent['key'] = $key;
		$this->parent['obj'] = $item;
	}

	/**
	 * Sets the current default menu item, to show if there arn't any "selections"
	 *
	 * @param string $key
	 * @param array $item
	 * @return void
	 */
	private function set_default_item( $key, $item ) {
		$this->default['key'] = $key;
		$this->default['obj'] = $item;
	}

	/**
	 * Sets the label and url of the parent if there is a "selected" or "default" item.
	 *
	 * @return void
	 */
	private function set_new_parent() {
		if ( ! empty( $this->parent ) ) {
			$new_parent = false;
			if ( ! empty( $this->selected ) ) {
				$new_parent = $this->selected;
			} else if ( ! empty( $this->default ) ) {
				$new_parent = $this->default;
				unset( $this->menu[ $new_parent['key'] ] );
			}

			if ( false !== $new_parent ) {
				//$this->menu[ $this->parent['key'] ]->title = $this->menu[ $this->parent['key'] ]->title . ' ' . $new_parent['obj']->title;
				$this->menu[ $this->parent['key'] ]->title = $new_parent['obj']->title;
				$this->menu[ $this->parent['key'] ]->url = $new_parent['obj']->url;
			}
		}
	}
}
