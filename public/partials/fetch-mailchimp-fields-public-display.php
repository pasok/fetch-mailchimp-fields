<?php
/**
 * Provide a public-facing view for the plugin
 * This file is used to markup the public-facing aspects of the plugin.
 */
?>
<div id='fetch-mailchimp-fields-app'
    data-field-names='<?php echo $this->shortcode_atts['field_names']; ?>'
    data-all-field-details='<?php echo $this->mailchimp_api->get_all_merge_fields_of_list(); ?>' >
</div>

<script>
    window.ajaxurl             = '<?php echo admin_url('admin-ajax.php') ?>';
    window.dataFieldNames      = '<?php echo $this->shortcode_atts['field_names']; ?>';
    window.dataAllFieldDetails = '<?php echo $this->mailchimp_api->get_all_merge_fields_of_list(); ?>';
</script>
