<!-- Provide a public-facing view for the plugin -->
<div id="<?php echo $this->plugin_name ?>-app">
    <mailchimp-search
        ajax-url='<?php echo admin_url('admin-ajax.php') ?>'
        action='<?php echo $this->shortcode_name ?>'
        nonce-token='<?php echo wp_create_nonce($this->shortcode_name) ?>'
        field-names='<?php echo $this->shortcode_atts['field_names'] ?>'
        field-display-names='<?php echo $this->mailchimp_api->get_all_merge_fields_of_list() ?>'
        >
    </mailchimp-search>
</div>
