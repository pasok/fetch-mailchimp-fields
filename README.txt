=== Fetch Mailchimp Fields ===
Contributors: nosoka
Donate link: http://twitter.com/nosokam
Tags: shortcode, api, mailchimp, merge fields,
Requires at least: 4.0
Tested up to: 5.1.1
Stable tag: trunk
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
This wordpress plugin gets the merge fields of a mailchimp subscriber.
- know about mailchimp merge fields, here.
    - https://mailchimp.com/help/manage-audience-signup-form-fields/
    - https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members
    - https://developer.mailchimp.com/documentation/mailchimp/reference/lists/merge-fields

== Installation ==
- Download latest version - https://bitbucket.org/pasok/fetch-mailchimp-fields/get/latest.zip
- Login to admin panel of the wordpress site
- Go to Plugins -> Add New -> Upload Plugin
    - upload the zipfile
    - install the plugin
    - activate the plugin

== Usage ==
- Login to admin panel of the wordpress site
- Go to MailChimp Config. Set following fields
    - Mailchimp List Id (ref: https://mailchimp.com/help/find-audience-id)
    - Mailchimp Api Key (ref: https://mailchimp.com/help/about-api-keys/#Find_or_Generate_Your_API_Key)
- Place `[fetch_mailchimp_fields]` shortcode in any post/page to use the plugin

== Optional attributes ==
- field_names
    - comma seperated list of fields to display in the result.
    - ex: `[fetch_mailchimp_fields field_names=FNAME, LNAME]` will fetch and show only FNAME, LNAME fields.

== Screenshots ==
1. Get merge field values of a mailchimp subscriber

== Changelog ==
= 1.5.0 =
* added field_names attribute to allow user to specify list of fields to be shown
* moved all mailchimp related methods into a seperate wrapper class
* decoupled tailwindcss into its own file so end user can make custom changes to fetch-mailchimp-fields-public.css
* displaying merge field names instead of tags in result set
* made the plugin backward compatible and tested till 4.0

= 1.0.0 =
* Initial release
