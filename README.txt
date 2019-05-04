=== Fetch Mailchimp Fields ===
Contributors: nosoka
Donate link: http://twitter.com/nosokam
Tags: mailchimp, merge fields, shortcode, api,
Requires at least: 4.0
Tested up to: 5.1.1
Stable tag: trunk
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


== Description ==
This plugin looks up a Subscriber in MailChimp list and shows their merge fields.


=== Usage ===
- Login to admin panel of the wordpress site
- Go to MailChimp Config. Set following fields
    - **Mailchimp List Id** - [mailchimp.com/help/find-audience-id](https://mailchimp.com/help/find-audience-id)
    - **Mailchimp Api Key** - [mailchimp.com/help/about-api-keys](https://mailchimp.com/help/about-api-keys/#Find_or_Generate_Your_API_Key)
- Place following shortcode in any post/page to use the plugin
    - **[fetch_mailchimp_fields]**


== Optional attributes ==
- field_names
    - comma seperated string with list of fields
    - following shortcode will show only FNAME, LNAME fields.
        - **[fetch_mailchimp_fields field_names='FNAME, LNAME']**


== Changelog ==
= 1.6.0 =
* added client/server side validations
* setup npm build process for all js/css files that are used.
* added nonce security check

= 1.5.0 =
* added field_names attribute to allow user to specify list of fields to be shown
* moved all mailchimp related methods into a seperate wrapper class
* decoupled tailwindcss into its own file so end user can make custom changes to fetch-mailchimp-fields-public.css
* displaying merge field names instead of tags in result set
* made the plugin backward compatible and tested till 4.0

= 1.0.0 =
* Initial release


== Screenshots ==
1. Get merge field values of a MailChimp Subscriber
