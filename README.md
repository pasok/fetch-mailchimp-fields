## Fetch Mailchimp Fields
This wordpress plugin gets the merge fields of a mailchimp subscriber.

- know about mailchimp merge fields, here.
    - https://mailchimp.com/help/manage-audience-signup-form-fields/
    - https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members
    - https://developer.mailchimp.com/documentation/mailchimp/reference/lists/merge-fields

## Installation
- Download latest version - https://bitbucket.org/pasok/fetch-mailchimp-fields/get/latest.zip
- Login to admin panel of the wordpress site
- Go to Plugins -> Add New -> Upload Plugin
    - upload the zipfile
    - install the plugin
    - activate the plugin

## Usage
- Login to admin panel of the wordpress site
- Go to MailChimp Config. Set following fields
    - Mailchimp List Id (ref: https://mailchimp.com/help/find-audience-id)
    - Mailchimp Api Key (ref: https://mailchimp.com/help/about-api-keys/#Find_or_Generate_Your_API_Key)
- Place `[fetch_mailchimp_fields]` shortcode in any post/page to use the plugin

## Optional attributes
- field_names
    - comma seperated list of fields to display in the result.
    - ex: `[fetch_mailchimp_fields field_names=FNAME, LNAME]` will fetch and show only FNAME, LNAME fields.

## demo
![Demo](./demo.gif?raw=true)

