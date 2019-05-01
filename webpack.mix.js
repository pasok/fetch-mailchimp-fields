const tailwindcss = require('tailwindcss');
const mix = require('laravel-mix');

mix.js('public/src/js/fetch-mailchimp-fields-public.js', 'public/js')
    .postCss('public/src/css/tailwind.css', 'public/css', [ tailwindcss('./tailwind.config.js') ]
);
