<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/admin
 * @author     Asok P
 */
class Fetch_Mailchimp_Fields_Admin {

    private $plugin_name;

    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Add additional link to plugin on the plugins list page
        add_filter('plugin_action_links', [$this, 'setup_action_links'], 10, 5);

        // Lets add an action to setup the admin menu in the left nav
        add_action( 'admin_menu', [$this, 'setup_menu']);

        // Add some actions to setup the settings we want on the wp admin page
        add_action('admin_init', [$this, 'setup_sections']);
        add_action('admin_init', [$this, 'setup_fields']);

    }

    /**
     * Add links to plugin list table
     * @param  array $links Existing links
     * @return array        Modified links
     */
    public function setup_action_links($links, $plugin_file) {
        if (preg_match("/{$this->plugin_name}/", $plugin_file)) {
            $settings_url = admin_url("admin.php?page={$this->plugin_name}");
            array_push($links, "<a href='{$settings_url}'>Settings</a>");
        }

        return $links;
    }

    /**
     * Add menu items to the admin menu
     */
    public function setup_menu() {
        add_menu_page('MailChimp Config', 'MailChimp', 'manage_options', $this->plugin_name, [$this, 'menu_callback'], 'dashicons-email');
    }

    /**
     * Callback function for displaying the admin settings page.
     */
    public function menu_callback() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/fetch-mailchimp-fields-admin-display.php';
    }

    /**
     * Setup sections in the settings
     */
    public function setup_sections() {
        add_settings_section( 'section_one', 'MailChimp Configuration', [$this, 'section_callback'], $this->plugin_name );
    }

    /**
     * Callback for each section
     */
    public function section_callback($arguments = []) {
        if (!isset($arguments['id'])) { return false; }

        if ($arguments['id'] == 'section_one') {
            echo "<p>Update these fields from your mailchimp dashboard </p>";
        }
    }

    /**
     * Field Configuration, each item in this array is one field/setting we want to capture
     */
    public function setup_fields() {

        // define properties of each fields
        $fields = [
            [
                'section'    => 'section_one',
                'uid'         => 'mailchimp_config_list_id',
                'type'        => 'text',
                'default'     => '',
                'label'       => 'Mailchimp List Id',
                'placeholder' => 'Enter your Mailchimp List Id',
                'description' => '<a target="_blank" href="https://mailchimp.com/help/find-audience-id">https://mailchimp.com/help/find-audience-id</a>',
            ],
            [
                'section'     => 'section_one',
                'uid'         => 'mailchimp_config_api_key',
                'type'        => 'text',
                'default'     => '',
                'label'       => 'Mailchimp API Key',
                'placeholder' => 'Enter your Mailchimp API Key',
                'description' => '<a target="_blank" href="https://mailchimp.com/help/about-api-keys/#Find_or_Generate_Your_API_Key">https://mailchimp.com/help/about-api-keys</a>',
            ],
        ];

        // Lets go through each field in the array and set it up
        foreach( $fields as $field ){
            add_settings_field( $field['uid'], $field['label'], [$this, 'field_callback'], $this->plugin_name, $field['section'], $field );
            register_setting( $this->plugin_name, $field['uid'] );
        }
    }

    /**
     * This handles all types of fields for the settings
     */
    public function field_callback($arguments) {
        // just making it easier for dev for better typing/reading
        $args = (object) $arguments;

        // Set value of the input field to that of whats in the DB or use a default value
        $value = !empty(get_option($args->uid)) ? get_option($args->uid) : $args->default;

        // setup the input element we are trying to display. can be extended for other field types later.
        if (array_key_exists('type', $arguments) && $args->type == 'text') {
            echo "<input size='40' name='{$args->uid}' type='{$args->type}' placeholder='{$args->placeholder}' value='{$value}' />";
        }

        // If there is helper text, lets show it.
        if (array_key_exists('helper', $arguments)) {
            echo "<span class='helper'>{$args->helper}</span>";
        }

        // If there is supplemental text lets show it.
        if (array_key_exists('description', $arguments)) {
            echo "<p class='description'>{$args->description}</p>";
        }
    }

    /**
     * This displays the notice in the admin page for the user
     */
    public function admin_notice($message) {
        echo "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";
    }

    /**
     * Register stylesheets files for the admin area.
     */
    public function enqueue_styles() {
        // enable when you want to style the options page
        // wp_enqueue_style( $this->plugin_name, plugins_url('css/fetch-mailchimp-fields-admin.css', __FILE__), [], $this->version, 'all');
    }

    /**
     * Register JavaScript files for the admin area.
     */
    public function enqueue_scripts() {
        // enable when you want to use custom javascript on the options page
        // wp_enqueue_script( $this->plugin_name, plugins_url('js/fetch-mailchimp-fields-admin.js', __FILE__), ['jquery'], $this->version, true);
    }
}
