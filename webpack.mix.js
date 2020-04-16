const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js('resources/js/app.js',                   'public/js')
  .js('resources/js/login.js',                 'public/js')
  .js('resources/js/aut_page.js',              'public/js')
  .js('resources/js/operator.js',              'public/js')
  .js('resources/js/admin/add_user.js',        'public/js/admin')
  .js('resources/js/admin/white_ip.js',        'public/js/admin')
  .js('resources/js/admin/list_user.js',       'public/js/admin')
  .js('resources/js/admin/access.js',          'public/js/admin')
  .js('resources/js/admin/profile.js',         'public/js/admin')
  .js('resources/js/admin/statistic.js',       'public/js/admin')
  .js('resources/js/admin/all_statistic.js',   'public/js/admin')
  .js('resources/js/admin/add_permission.js',  'public/js/admin')
  .js('resources/js/admin/list_permission.js', 'public/js/admin')
  .js('resources/js/dashboard.js',             'public/js')
  .js('resources/js/admin/edit_form.js',       'public/js/admin')
  .js('resources/js/admin/add_tack.js',        'public/js/admin')
  .js('resources/js/admin/tasks_list.js',      'public/js/admin')
  .js('resources/js/admin/unit_tack.js',       'public/js/admin')

  .js('resources/js/admin/call_list.js',       'public/js/admin')
  .js('resources/js/admin/notice_list.js',       'public/js/admin')
  .js('resources/js/admin/tasks_list_dash.js', 'public/js/admin')

  .sass('resources/sass/app.core.scss', 'public/css') ;
