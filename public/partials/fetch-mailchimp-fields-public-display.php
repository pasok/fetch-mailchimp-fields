<?php
/**
 * Provide a public-facing view for the plugin
 * This file is used to markup the public-facing aspects of the plugin.
 */
?>
<div id='fetch-mailchimp-fields-app'
    data-action="<?php echo $this->shortcode_name ?>"
    data-nonce-token="<?php echo wp_create_nonce($this->shortcode_name) ?>"
    data-ajax-url="<?php echo admin_url('admin-ajax.php') ?>"
    data-field-names="<?php echo $this->shortcode_atts['field_names'] ?>"
    data-field-display-names="<?php echo $this->mailchimp_api->get_all_merge_fields_of_list() ?>" >
</div>
