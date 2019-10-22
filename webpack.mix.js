let mix = require('laravel-mix');

require('laravel-mix-polyfill');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix
    .combine('assets/scripts/grid-gallery.js', 'assets/js/grid-gallery.min.js')
    .combine(['assets/scripts/add-to-cart.js', 'assets/scripts/cart-page.js', 'assets/scripts/checkout.js'], 'assets/js/add-to-cart.min.js')
    .combine('assets/scripts/admin/order-detail.js', 'assets/js/admin/order-detail.min.js')
    .sass('assets/scss/admin.style.scss', 'assets/css/admin.style.min.css')
    .sass('assets/scss/storefront.scss', 'assets/css/storefront.min.css')
    .sass('assets/scss/email_templates/css.scss', 'templates/emails/css.css')
    .sass('assets/scss/style.scss', 'assets/css/style.min.css')
    .setPublicPath('assets')
    .sourceMaps(true, 'source-map')
    .options({
        processCssUrls: false
    });

