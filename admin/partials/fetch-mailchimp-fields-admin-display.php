<!-- Provide admin area view for the plugin -->
<div class="wrap">
    <form method="POST" action="options.php">
    <?php
        echo (isset($_GET['settings-updated'])) ? $this->admin_notice("Your settings have been updated!") : '';

        do_settings_sections($this->plugin_name);
        settings_fields($this->plugin_name);
        submit_button();
    ?>
    </form>
</div>
