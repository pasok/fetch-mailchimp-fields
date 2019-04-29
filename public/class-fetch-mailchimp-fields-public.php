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

        add_action('wp_ajax_fetch_mailchimp_fields', [$this, 'ajax_callback']);

        add_action('wp_ajax_nopriv_fetch_mailchimp_fields', [$this, 'ajax_callback']);
    }

    /**
     * callback function to display shortcode
     * Generate html markup for the public-facing side of the site.
     */
    public function shortcode_callback($atts) {
        // if (!is_user_logged_in()) { return false; }
        $this->shortcode_atts = shortcode_atts($this->shortcode_atts, $atts );

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/fetch-mailchimp-fields-public-display.php';
    }

    /**
     * Register stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        if (has_shortcode(get_post()->post_content, $this->shortcode_name)) {
            wp_enqueue_style($this->plugin_name, plugins_url('css/fetch-mailchimp-fields-public.css', __FILE__ ), [], $this->version, 'all' );
            wp_enqueue_style('tailwindcss', plugins_url('css/tailwind.css', __FILE__ ), [], $this->version, 'all' );
        }
    }

    /**
     * Register JavaScript file for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        if (has_shortcode(get_post()->post_content, $this->shortcode_name)) {
            wp_enqueue_script('vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js', [], '2.6.10' );
            wp_enqueue_script($this->plugin_name, plugins_url('js/fetch-mailchimp-fields-public.js', __FILE__ ), ['vue'], $this->version, true);
        }
    }

    /**
     * call mailchimp api to get merge fields of specific user
     * ref: https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members
     * TODO:: make a class for all mailchimp methods
     */
    public function ajax_callback() {
        $email = trim($_POST['email']);
        $field_names = [];

        //TODO:: add email validation
        if ($email == '') {
            exit(json_encode(['error' => 'Email is required']));
        }
        if (isset($_POST['field_names']) && !empty($_POST['field_names'])) {
            $field_names = array_flip(array_map(function($value) {
                    return strtoupper(trim($value));
                }, explode(',', $_POST['field_names'])
            ));
        }

        if (!$serverData = $this->mailchimp_api->get_merge_fields_of_member($email)) {
            exit(json_encode(['error' => $this->mailchimp_api->get_error()]));
        }
        if(sizeof($field_names) > 0) {
            exit(json_encode(array_intersect_key($serverData, $field_names)));
        }

        //TODO:: add crosschecks for error responses from mailchimp api
        exit(json_encode($serverData));
    }
}
