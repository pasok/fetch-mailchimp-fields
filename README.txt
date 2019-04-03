=== Fetch Mailchimp Fields ===
This wordpress plugin fetches merge fields of a subscriber from a mailchimp list

== Installation Steps ==
- Download the repository zip file and unzip.
- Rename the unzipped folder to `fetch-mailchimp-fields`
- Set variables in `fetch-mailchimp-fields/public/class-fetch-mailchimp-fields-public.php` file
    mailchimp_list_id
    mailchimp_api_key
- Upload `fetch-mailchimp-fields` folder to the `/wp-content/plugins/`
- Activate the plugin through the 'Plugins' menu in WordPress
- Place [fetch_mailchimp_fields] in any post/page to display the form
