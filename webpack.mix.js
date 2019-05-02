const tailwindcss = require('tailwindcss');
const mix = require('laravel-mix');

mix.js('public/assets/src/js/fetch-mailchimp-fields-public.js', 'public/assets/dist/js')
    .postCss('public/assets/src/css/fetch-mailchimp-fields-public.css', 'public/assets/dist/css', [ tailwindcss('./tailwind.config.js') ]
);
