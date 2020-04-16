<?php

Route::get('/clear', function () {
  Artisan::call('cache:clear');
  Artisan::call('config:clear');
//    Artisan::call('config:cache');
  Artisan::call('view:clear');
  Artisan::call('route:clear');
//    Artisan::call('backup:clean');
  return "Кэш очищен.";
});


Route::get('/', function () {
  return redirect()->route('users.common.dashboard');
});
Route::get('/home', function () {
  return redirect()->route('users.common.dashboard');
});
Route::get('/logout', 'Auth\LoginController@logout')->name('users.common.logout');
Route::get('/aut_page', 'Adm\Users\PagesController@autPage')->name('aut_page');

Auth::routes();

Route::post("/login","Auth\LoginController@login")->middleware("throttle:50,1");

Route::post('/unic_email', 'Adm\Users\ProfileController@unicEmail');

Route::group(['middleware' => 'auth'], function () {

  Route::match(['get', 'post'], '/dashboard', 'HomeController@index')->name('users.common.dashboard');

  // =========================== Пользователи
  // Настройка профиля
  Route::get('/my_profile',      ['as' => 'admin.my_profile', 'uses' => 'Adm\Users\ProfileController@index']);
  Route::post('/profile_update', ['as' => 'profile.update',   'uses' => 'Adm\Users\ProfileController@chengeCommonInfo']);
  Route::post('/verification_password', ['as' => 'admin.verification_password', 'uses' => 'Adm\Users\ProfileController@verificationSelfPass']);
  Route::post('/chenge_self_passw', ['as' => 'admin.chenge_self_passw', 'uses' => 'Adm\Users\ProfileController@chengeSelfPassw']);
  Route::post('/chenge_self_descr', ['as' => 'admin.chenge_self_descr', 'uses' => 'Adm\Users\ProfileController@chengeSelfDescr']);

  // Права доступа
  Route::get('/access_pages', ['as' => 'access_pages', 'uses' => 'Adm\Users\PagesController@accessPages']);
  Route::get('/access_pages', ['as' => 'access_pages', 'uses' => 'Adm\Users\PagesController@accessPages']);
  Route::post('/users_change_access', ['as' => 'api.access_pages', 'uses' => 'Adm\Users\PostAjaxController@usersChangeAccess']);

  // Список пользователей
  Route::get('/list_users', ['as' => 'list_users', 'uses' => 'Adm\Users\PagesController@usersList']);
  Route::match(['get', 'post'], '/users_profile', ['as' => 'users_profile', 'uses' => 'Adm\Users\PagesController@usersProfile']);
  Route::match(['get', 'post'], '/user_statistic_page', ['as' => 'user_statistic_page', 'uses' => 'Adm\Users\PagesController@userStatisticPage']);

  Route::get('/statisticExel', ['as' => 'statisticExel', 'uses' => 'Adm\Users\PagesController@statisticExel']);

  Route::get('/add_users', ['as' => 'add_users', 'uses' => 'Adm\Users\PagesController@usersAdd']);
  Route::post('/add_users', ['as' => 'api.add_users', 'uses' => 'Adm\Users\PostAjaxController@usersAddAPI']);

  Route::match(['get', 'post'], '/list_operators_statistic',  ['as' => 'list_operators_statistic', 'uses' => 'Adm\Users\PagesController@listOperatorsStatistic']);
  Route::get('/exel_operators_statistic', ['as' => 'exel_operators_statistic', 'uses' => 'Adm\Users\PagesController@listOperatorsStatisticExell']);

  Route::post('/users_ban', ['as' => 'users_ban', 'uses' => 'Adm\Users\PostAjaxController@banUser']);
  Route::post('/delete_user', ['as' => 'delete_user', 'uses' => 'Adm\Users\PostAjaxController@deleteUser']);
  Route::post('/change_users_lavel', ['as' => 'change_users_lavel', 'uses' => 'Adm\Users\PostAjaxController@chenLevelUser']);
  Route::post('/chenge_users_passw', ['as' => 'chenge_users_passw', 'uses' => 'Adm\Users\PostAjaxController@chenPasswUser']);

  // Белые IP
  Route::get('/white_ip_page', ['as' => 'white_ip_page', 'uses' => 'Adm\Users\PagesController@whiteIP']);
  Route::get('/white_ip_page', ['as' => 'white_ip_page', 'uses' => 'Adm\Users\PagesController@whiteIP']);

  Route::post('/white_ip_add', 'Adm\Users\PostAjaxController@whiteIPAdd')->name('white_ip_add');
  Route::post('/white_ip_del', 'Adm\Users\PostAjaxController@whiteIPDel')->name('white_ip_del');

  // =========================== KPI
  Route::match(['get', 'post'],'/kpi_page', 'Adm\Users\PagesController@kpiPages')->name('kpi_page');
  Route::get('/exel_kpi', 'Adm\Users\PagesController@kpiPagesExcel')->name('exel_kpi');

  // =========================== Роли
  Route::get('/permissions_add',  'Adm\Setting\PagesController@permissionsAdd')->name('permissions_add');
  Route::get('/permissions_list', 'Adm\Setting\PagesController@permissionsList')->name('permissions_list');
  Route::post('/permissions_add', 'Adm\Setting\PagesController@permissionsAddApi')->name('api.permissions_add');
  Route::post('/delete_permission_group', 'Adm\Setting\PagesController@permissionsDelApi')->name('api.permissions_del');


  // =========================== Клиенты
  Route::get('/clients_list_page', 'Adm\Clients\PagesController@clientsList')->name('clients_list_page');


  // =========================== Звонки
  Route::match(['get', 'post'],'/call_list_page',       'Adm\Tacks\PagesController@callListPage')->name('call_list_page');
  Route::match(['get', 'post'],'/call_user_page', 'Adm\Tacks\PagesController@callUserPage')->name('call_user_page');

  // =========================== Уведомления
  Route::match(['get', 'post'],'/notice_user_page', 'Adm\Notice\PagesController@noticeUserPage')->name('notice_user_page');

  // =========================== Обращения
  Route::post('/tack_list_page', 'Adm\Tacks\PagesController@tackList')->name('tack_list_page');
  Route::get('/task_exel',       'Adm\Tacks\PagesController@taskExel')->name('task_exel');
  Route::get('/tack_list_page',  'Adm\Tacks\PagesController@tackListGet')->name('tack_list_page');


  Route::get('/my_tack_page_developer',  'Adm\Tacks\PagesController@tackDeveloperGet')->name('my_tack_page_developer');
  Route::post('/my_tack_page_developer', 'Adm\Tacks\PagesController@tackDeveloper')->name('my_tack_page_developer');


  Route::post('/cons_list_page', 'Adm\Tacks\PagesController@consList')->name('cons_list_page');
  Route::get('/cons_list_page',  'Adm\Tacks\PagesController@consListGet')->name('cons_list_page');

  Route::get('/tack_add_page',  'Adm\Tacks\PagesController@tackAdd')->name('tack_add_page');
  Route::post('/tack_add_page', 'Adm\Tacks\PagesController@tackAddApi')->name('tack_add_page');

  Route::post('/gov_group_add', 'Adm\Tacks\PagesController@govGroupAdd')->name('gov_group_add');
  Route::post('/gov_group_del', 'Adm\Tacks\PagesController@govGroupDel')->name('gov_group_del');
  Route::get('/tack_form_edit_page', 'Adm\Tacks\PagesController@tackEditForm')->name('tack_form_edit_page');
  Route::post('/prior_days',     'Adm\Tacks\PagesController@priorDays')->name('prior_days');

  Route::post('/gov_add', 'Adm\Tacks\PagesController@govAdd')->name('gov_add');
  Route::post('/gov_del', 'Adm\Tacks\PagesController@govDel')->name('gov_del');

  Route::post('/form_add', 'Adm\Tacks\PagesController@formAdd')->name('form_add');
  Route::post('/form_del', 'Adm\Tacks\PagesController@formDel')->name('form_del');

  Route::post('/task_del',         'Adm\Tacks\PagesController@taskDel')->name('task_del');
  Route::post('/close_tack_operator',         'Adm\Tacks\PagesController@taskClose')->name('close_tack_operator');
  Route::post('/upload_additions', 'Adm\Tacks\PagesController@uploadAdditions')->name('upload_additions');

  Route::post('/task_edit',           'Adm\Tacks\PagesController@taskEdit')->name('task_edit');
  Route::post('/task_edit_developer', 'Adm\Tacks\PagesController@taskEditDeveloper')->name('task_edit_developer');

  Route::post('/process_names',  'Adm\Tacks\PagesController@processNamesAdd')->name('process_names');
  Route::post('/name_group_del', 'Adm\Tacks\PagesController@processNamesDel')->name('name_group_del');

  Route::match(['get', 'post'], '/tack_cons_page', 'Adm\Tacks\PagesController@tackConsOperator')->name('tack_cons_page');
  Route::match(['get', 'post'], '/my_tack_page_operator', 'Adm\Tacks\PagesController@tackOperator')->name('my_tack_page_operator');


  // =========================== Рассылка
  Route::match(['get', 'post'],'/unit_mail_page', 'Adm\Mail\PagesController@unitMailPage')->name('unit_mail_page');
  Route::match(['get', 'post'],'/add_mail_page',  'Adm\Mail\PagesController@addMailPage')->name('add_mail_page');
  Route::match(['get', 'post'],'/mail_page',      'Adm\Mail\PagesController@mailPage')->name('mail_page');
  Route::match(['get', 'post'],'/my_mail_page',   'Adm\Mail\PagesController@myMailPage')->name('my_mail_page');

  Route::post('/send_usermail',                   'Adm\Mail\PagesController@sendMail')->name('send_usermail');
  Route::post('/del_mail',                        'Adm\Mail\PagesController@delMail')->name('del_mail');


});


Route::get('/get_calls',      'Adm\Tacks\PagesController@getCalls')->name('tack.common.get_calls');

Route::get('/soc_logout', 'ChatsController@parserBK')->name('soc_logout');

