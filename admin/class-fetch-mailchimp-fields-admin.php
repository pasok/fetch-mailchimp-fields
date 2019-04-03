<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       wordpress.org
 * @since      1.0.0
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/admin
 * @author     Asok P <asok@fastmail.com>
 */
class Fetch_Mailchimp_Fields_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Lets add an action to setup the admin menu in the left nav
		add_action( 'admin_menu', array($this, 'add_admin_menu') );

		// Add some actions to setup the settings we want on the wp admin page
		add_action('admin_init', array($this, 'setup_sections'));
		add_action('admin_init', array($this, 'setup_fields'));
	}

	/**
	 * Add the menu items to the admin menu
	 *
	 * @since    2.0.0
	 */
	public function add_admin_menu() {

		// Main Menu Item
	  	add_menu_page(
			'MailChimp Configuration',
			'MailChimp Config',
			'manage_options',
			'mailchimp-config',
			array($this, 'display_plugin_admin_page'),
			'dashicons-email'
		);
	}

	/**
	 * Callback function for displaying the admin settings page.
	 *
	 * @since    2.0.0
	 */
	public function display_plugin_admin_page(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/fetch-mailchimp-fields-admin-display.php';
	}

	/**
	 * Setup sections in the settings
	 *
	 * @since    2.0.0
	 */
	public function setup_sections() {
		add_settings_section( 'section_one', 'MailChimp Configuration', array($this, 'section_callback'), 'mailchimp-config-options' );
	}

	/**
	 * Callback for each section
	 *
	 * @since    2.0.0
	 */
	public function section_callback( $arguments ) {
		switch( $arguments['id'] ){
			case 'section_one':
				echo '<p>Update these fields from your mailchimp dashboard </p>';
				break;
		}
	}

	/**
	 * Field Configuration, each item in this array is one field/setting we want to capture
	 *
	 * @since    2.0.0
	 */
	public function setup_fields() {
		$fields = array(
			array(
				'uid' => 'mailchimp_config_list_id',
				'label' => 'Mailchimp List Id',
				'section' => 'section_one',
				'type' => 'text',
				'placeholder' => 'Mailchimp list id',
				'supplemental' => '<a target="_blank" href="https://mailchimp.com/help/find-audience-id">https://mailchimp.com/help/find-audience-id</a>',
			),
			array(
				'uid' => 'mailchimp_config_api_key',
				'label' => 'Mailchimp API Key',
				'section' => 'section_one',
				'type' => 'text',
				'placeholder' => 'Mailchimp api key',
				'supplemental' => '<a target="_blank" href="https://mailchimp.com/help/about-api-keys/#Find_or_Generate_Your_API_Key">https://mailchimp.com/help/about-api-keys</a>',
			),
		);
		// Lets go through each field in the array and set it up
		foreach( $fields as $field ){
			add_settings_field( $field['uid'], $field['label'], array($this, 'field_callback'), 'mailchimp-config-options', $field['section'], $field );
			register_setting( 'mailchimp-config-options', $field['uid'] );
		}
	}

	/**
	 * This handles all types of fields for the settings
	 *
	 * @since    2.0.0
	 */
	public function field_callback($arguments) {
		// Set our $value to that of whats in the DB
		$value = get_option( $arguments['uid'] );
		// Only set it to default if we get no value from the DB and a default for the field has been set
		if(!$value) {
			$value = $arguments['default'];
		}
		// Lets do some setup based ont he type of element we are trying to display.
		switch( $arguments['type'] ){
			case 'text':
				printf( '<input size="40" name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
				break;
		}
		// If there is helper text, lets show it.
		if( array_key_exists('helper',$arguments) && $helper = $arguments['helper']) {
			printf( '<span class="helper"> %s</span>', $helper );
		}
		// If there is supplemental text lets show it.
		if( array_key_exists('supplemental',$arguments) && $supplemental = $arguments['supplemental'] ){
			printf( '<p class="description">%s</p>', $supplemental );
		}
	}

	/**
	 * Admin Notice
	 *
	 * This displays the notice in the admin page for the user
	 *
	 * @since    2.0.0
	 */
	public function admin_notice($message) { ?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo($message); ?></p>
		</div><?php
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fetch_Mailchimp_Fields_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fetch_Mailchimp_Fields_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fetch-mailchimp-fields-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fetch_Mailchimp_Fields_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fetch_Mailchimp_Fields_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fetch-mailchimp-fields-admin.js', array( 'jquery' ), $this->version, false );

	}

}
