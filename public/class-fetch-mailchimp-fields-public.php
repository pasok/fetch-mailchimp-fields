<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/public
 * @author     Asok P <asok@fastmail.com>
 */
class Fetch_Mailchimp_Fields_Public {

    private $plugin_name;
    private $version;
    private $shortcode_name = 'fetch_mailchimp_fields';
    private $shortcode_atts = ['field_names' => null];

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode($this->shortcode_name, [$this, 'fetch_mailchimp_fields_shortcode']);

        add_action('wp_ajax_fetch_mailchimp_fields', [$this, 'fetch_mailchimp_fields']);

        add_action('wp_ajax_nopriv_fetch_mailchimp_fields', [$this, 'fetch_mailchimp_fields']);
    }

    /**
     * callback function to display shortcode
     * Generate html markup for the public-facing side of the site.
     */
    public function fetch_mailchimp_fields_shortcode($atts) {
        // if (!is_user_logged_in()) { return false; }
        $this->shortcode_atts = shortcode_atts($this->shortcode_atts, $atts );

        return "<div id='fetch-mailchimp-fields-app' data-field-names='{$this->shortcode_atts['field_names']}'></div>";
    }

    /**
     * Register stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles() {
        if (has_shortcode(get_post()->post_content, $this->shortcode_name)) {
            wp_enqueue_style($this->plugin_name, plugins_url('css/fetch-mailchimp-fields-public.css', __FILE__ ), [], $this->version, 'all' );
        }
    }

    /**
     * Register JavaScript file for the public-facing side of the site.
     */
    public function enqueue_scripts() {
        if (has_shortcode(get_post()->post_content, $this->shortcode_name)) {
            wp_enqueue_script('vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js', [], '2.6.10' );
            wp_enqueue_script($this->plugin_name, plugins_url('js/fetch-mailchimp-fields-public.js', __FILE__ ), ['vue'], $this->version, true);
            wp_add_inline_script($this->plugin_name, 'window.ajaxurl = "' . admin_url('admin-ajax.php') . '"');
        }
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
}
