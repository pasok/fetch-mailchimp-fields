<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       wordpress.org
 * @since      1.0.0
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/public
 * @author     Asok P <asok@fastmail.com>
 */
class Fetch_Mailchimp_Fields_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    private $shortcode_name    = 'fetch_mailchimp_fields';

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
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode( $this->shortcode_name, [$this, 'shortcode'] );
        add_action('wp_ajax_fetch_mailchimp_fields', array($this, 'fetch_mailchimp_fields'));
        add_action('wp_ajax_nopriv_fetch_mailchimp_fields', [$this, 'fetch_mailchimp_fields'] );
    }

    /**
     * method that generates the html markup of the plugin
     */
    public function shortcode() {
        // if (!is_user_logged_in()) { return false; }

        return "<div id='fetch-mailchimp-fields-app'></div>";
    }

    /**
     * call mailchimp api to get merge fields of specific user
     * ref: https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members
     */
    public function fetch_mailchimp_fields() {
        $email = trim($_POST['email']);
        if ($email == '') {
            exit(json_encode(['error' => 'Email is required']));
        }

        try {
            $listId         = get_option('mailchimp_config_list_id');
            $apiKey         = get_option('mailchimp_config_api_key');
            $mailchimp      = new \DrewM\MailChimp\MailChimp($apiKey);
            $subscriberHash = $mailchimp->subscriberHash($email);
            $result         = $mailchimp->get("lists/{$listId}/members/{$subscriberHash}");
        } catch(Exception $e) {
            exit(json_encode(['error' => $e->getMessage()]));
        }

        if (!isset($result['merge_fields']) || !isset($result['merge_fields']['MERGE7'])) {
            exit(json_encode(['error' => "Failed to get Bedner's Bucks balance"]));
        }

        //TODO:: add crosschecks for error responses from mailchimp api
        exit(json_encode($result['merge_fields']));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fetch-mailchimp-fields-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        global $post;
        if( has_shortcode( $post->post_content, $this->shortcode_name ) ) {
            wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.js', [], '2.6.10' );
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fetch-mailchimp-fields-public.js', ['vue'], $this->version, true );
            wp_add_inline_script( $this->plugin_name, 'window.ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"');
        }
    }

}
