## Fetch Mailchimp Fields
- This wordpress plugin gets the merge fields of a mailchimp subscriber.
- know about mailchimp merge fields, here.
    - [mailchimp.com/help/manage-audience-signup-form-fields](https://mailchimp.com/help/manage-audience-signup-form-fields)
    - [developer.mailchimp.com/documentation/mailchimp/reference/lists/members](https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members)
    - [developer.mailchimp.com/documentation/mailchimp/reference/lists/merge-fields](https://developer.mailchimp.com/documentation/mailchimp/reference/lists/merge-fields)

<br>

## Installation
- Download latest version - [wordpress.org/plugins/fetch-mailchimp-fields](https://wordpress.org/plugins/fetch-mailchimp-fields)

<br>

## Development
```
git clone git@github.com:pasok/fetch-mailchimp-fields.git
npm install
npm run watch
```

<br>

## Usage
- Login to admin panel of the wordpress site
- Go to MailChimp Config. Set following fields
    - **Mailchimp List Id** - [mailchimp.com/help/find-audience-id](https://mailchimp.com/help/find-audience-id)
    - **Mailchimp Api Key** - [mailchimp.com/help/about-api-keys](https://mailchimp.com/help/about-api-keys/#Find_or_Generate_Your_API_Key)
- Place following shortcode in any post/page to use the plugin
    ```
    [fetch_mailchimp_fields]
    ```

<br>

## Optional attributes
- field_names
    - comma seperated string with list of fields
    - following shortcode will fetch and show only FNAME, LNAME fields.
        ```
        [fetch_mailchimp_fields field_names='FNAME, LNAME']
        ```

<br>

## Changelog
* 1.6.0
    * added client/server side validations
    * setup npm build process for all js/css files that are used.
    * added nonce security check
* 1.5.0
    * added field_names attribute to allow user to specify list of fields to be shown
    * moved all mailchimp related methods into a seperate wrapper class
    * decoupled tailwindcss into its own file so end user can make custom changes to fetch-mailchimp-fields-public.css
    * displaying merge field names instead of tags in result set
    * made the plugin backward compatible and tested till 4.0
* 1.0.0
    * Initial release

<br>

## Demo
![Demo](https://github.com/pasok/fetch-mailchimp-fields/raw/master/assets/screenshot-1.gif)
