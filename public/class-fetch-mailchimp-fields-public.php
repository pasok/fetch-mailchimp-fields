<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/public
 * @author     Asok P
 */
class Fetch_Mailchimp_Fields_Public {

    private $plugin_name;
    private $version;
    private $shortcode_name = 'fetch_mailchimp_fields';
    private $shortcode_atts = ['field_names' => null];

    public function __construct( $plugin_name, $version ) {
        $this->version = $version;
        $this->plugin_name = $plugin_name;
        $this->mailchimp_api = new \App\MailChimpApi();

        add_shortcode($this->shortcode_name, [$this, 'shortcode_callback']);

        add_action("wp_ajax_{$this->shortcode_name}", [$this, 'ajax_callback']);

        add_action("wp_ajax_nopriv_{$this->shortcode_name}", [$this, 'ajax_callback']);
    }

    /**
     * callback function to display shortcode
     * Generate html markup for the public-facing side of the site.
     */
    public function shortcode_callback($atts) {
        $this->shortcode_atts = shortcode_atts($this->shortcode_atts, $atts );

        require_once plugin_dir_path( dirname( __FILE__ ) ) . "public/partials/{$this->plugin_name}-public-display.php";
    }

    /**
     * call mailchimp api to get merge fields of specific user
     */
    public function ajax_callback() {
        $email = sanitize_email( $_POST['email'] );
        $field_names = $this->csvToArray( sanitize_text_field( $_POST['field_names'] ) );

        // return error if there is a mismatch in nonce_token
        if ( ! wp_verify_nonce( $_POST['nonce_token'], $this->shortcode_name ) ) {
            die( json_encode(['error' => __('Invalid token. Please crosscheck and retry')]));
        }
        // return error if input email is not valid
        if ( ! is_email( $email ) ) {
            die(json_encode(['error' => __('Invalid email address.')]));
        }
        // return error if mailchimp responded with error
        if (!$mailchimpData = $this->mailchimp_api->get_merge_fields_of_member($email)) {
            die(json_encode(['error' => $this->mailchimp_api->get_error()]));
        }

        // all is well. respond by filtering mailchimp data if field_names attr is provided
        if(is_array($field_names) and !empty($field_names)){
            die(json_encode(array_intersect_key($mailchimpData, $field_names)));
        }

        // all is well. respond with data received from mailchimp as is
        die(json_encode($mailchimpData));
    }

    /**
     * Helper method to convert comma seperated values in the string as keys in arrays
     */
    public function csvToArray($input = '') {
        if ( empty($input) || (strpos($input, ',') === false) ) { return $input; }

        return array_flip(array_map(function($value) {
                return strtoupper(trim($value));
            }, explode(',', $input)
        ));
    }

    /**
     * Register stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        if (has_shortcode(get_post()->post_content, $this->shortcode_name)) {
            wp_enqueue_style($this->plugin_name, plugins_url("{$this->plugin_name}/public/assets/dist/css/{$this->plugin_name}-public.css"), [], $this->version, 'all' );
            wp_enqueue_style("{$this->plugin_name}-style", plugins_url("{$this->plugin_name}/public/assets/style.css"), [], $this->version, 'all' );
        }
    }

    /**
     * Register JavaScript file for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        if (has_shortcode(get_post()->post_content, $this->shortcode_name)) {
            wp_enqueue_script($this->plugin_name, plugins_url("{$this->plugin_name}/public/assets/dist/js/{$this->plugin_name}-public.js"), [], $this->version, true);
        }
    }
}
