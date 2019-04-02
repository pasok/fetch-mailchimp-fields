<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       wordpress.org
 * @since      1.0.0
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/includes
 * @author     Asok P <asok@fastmail.com>
 */
class Fetch_Mailchimp_Fields_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'fetch-mailchimp-fields',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
