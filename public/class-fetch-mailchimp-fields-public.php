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

    private $shortcode_name = 'fetch_mailchimp_fields';

    private $shortcode_atts = ['field_names' => null];

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
     * Generate html markup for the public-facing side of the site.
     */
    public function shortcode($atts) {
        // if (!is_user_logged_in()) { return false; }
        $this->shortcode_atts = shortcode_atts($this->shortcode_atts, $atts );

        return "<div id='fetch-mailchimp-fields-app' data-field-names='{$this->shortcode_atts['field_names']}'></div>";
    }

    /**
     * call mailchimp api to get merge fields of specific user
     * ref: https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members
     * TODO:: make a class for all mailchimp methods
     */
    public function fetch_mailchimp_fields() {
        $email = trim($_POST['email']);
        $fieldNames = [];

        //TODO:: add email validation
        if ($email == '') {
            exit(json_encode(['error' => 'Email is required']));
        }
        if (isset($_POST['field_names']) && !empty($_POST['field_names'])) {
            $fieldNames = array_flip(array_map(function($value) {
                    return strtoupper(trim($value));
                }, explode(',', $_POST['field_names'])
            ));
        }

        try {
            $listId         = get_option('mailchimp_config_list_id');
            $apiKey         = get_option('mailchimp_config_api_key');
            $mailchimp      = new \DrewM\MailChimp\MailChimp($apiKey);
            $subscriberHash = $mailchimp->subscriberHash($email);
            $result         = $mailchimp->get("lists/{$listId}/members/{$subscriberHash}");
            $serverData     = isset($result['merge_fields']) ? $result['merge_fields'] : [];
        } catch(Exception $e) {
            exit(json_encode(['error' => $e->getMessage()]));
        }

        if (empty($serverData) || sizeof($serverData) < 1) {
            exit(json_encode(['error' => "Failed to fetch information from server"]));
        }
        if(sizeof($fieldNames) > 0) {
            exit(json_encode(array_intersect_key($serverData, $fieldNames)));
        }

        //TODO:: add crosschecks for error responses from mailchimp api
        exit(json_encode($serverData));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fetch-mailchimp-fields-public.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        global $post;
        if( has_shortcode( $post->post_content, $this->shortcode_name ) ) {
            wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js', [], '2.6.10' );
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fetch-mailchimp-fields-public.js', ['vue'], $this->version, true );
            wp_add_inline_script( $this->plugin_name, 'window.ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"');
        }
    }
}
