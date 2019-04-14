=== Fetch Mailchimp Fields ===
This wordpress plugin fetches merge fields of a subscriber from a mailchimp list

== Installation Steps ==
- Download latest version of plugin - https://bitbucket.org/pasok/fetch-mailchimp-fields/get/latest.zip
- Login to admin panel of the wordpress site
- Go to Plugins -> Add New -> Upload Plugin
    Upload downloaded zipfile
    install the plugin
    activate the plugin
- Go to MailChimp Config and set following fields
    Mailchimp List Id
    Mailchimp Api Key
- Place [fetch_mailchimp_fields] shortcode in any post/page to display the form
- shortcode accepts field_name attribute.
    [fetch_mailchimp_fields field_name='FNAME'] will fetch FNAME field
