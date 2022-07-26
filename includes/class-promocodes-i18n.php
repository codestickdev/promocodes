<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codestick.pl/
 * @since      1.0.1
 *
 * @package    Promocodes
 * @subpackage Promocodes/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.1
 * @package    Promocodes
 * @subpackage Promocodes/includes
 * @author     Piotr Gajewski <piotrdevv@gmail.om>
 */
class Promocodes_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'promocodes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
