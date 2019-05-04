const tailwindcss = require('tailwindcss');
const mix = require('laravel-mix');
const glob = require('glob-all');
require('laravel-mix-purgecss');

mix.js('public/assets/src/js/fetch-mailchimp-fields-public.js', 'public/assets/dist/js');

mix.postCss('public/assets/src/css/fetch-mailchimp-fields-public.css', 'public/assets/dist/css', [ tailwindcss('./tailwind.config.js') ])
    .purgeCss({
        enabled: true,
        paths: () => glob.sync([
            path.join(__dirname, 'public/assets/src/js/*.js'),
            path.join(__dirname, 'public/assets/src/js/components/*.vue'),
            path.join(__dirname, 'public/partials/*.php'),
        ]),
    });
