<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       wordpress.org
 * @since      1.0.0
 *
 * @package    Fetch_Mailchimp_Fields
 * @subpackage Fetch_Mailchimp_Fields/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <?php
    if (isset($_GET['settings-updated'])){
        $this->admin_notice("Your settings have been updated!");
    }
    ?>

    <form method="POST" action="options.php">
        <?php
        settings_fields('mailchimp-config-options');
        do_settings_sections('mailchimp-config-options');
        submit_button();
        ?>
    </form>
</div>
