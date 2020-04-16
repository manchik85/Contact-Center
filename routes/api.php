<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/check_user_access',    'Auth\LoginController@checkUser')->name('users.common.check_user');
Route::post('/operator_aut',         'Adm\Users\PostAjaxController@operatorAut')->name('users.common.operator_aut');
Route::post('/operator_in',          'Adm\Users\PostAjaxController@operatorIn')->name('users.common.operator_in');
Route::post('/get_permission_group', 'Adm\Users\PostAjaxController@getPermissionGroup')->name('users.common.get_permission_group');
Route::post('/get_gov_organs',       'Adm\Tacks\PagesController@getGovOrgans')->name('tack.common.get_gov_organs');
Route::post('/get_gov_organs_vue',   'Adm\Tacks\PagesController@getGovOrgansVue')->name('tack.common.get_gov_organs_vue');
Route::post('/get_phone_numbs_vue',   'Adm\Tacks\PagesController@getPhoneNumbsVue')->name('tack.common.get_phone_numbs_vue');
Route::post('/get_login_clients',    'Adm\Tacks\PagesController@getLoginClients')->name('tack.common.get_login_clients');
Route::post('/get_mail_clients',     'Adm\Tacks\PagesController@getMailClients')->name('tack.common.get_mail_clients');
Route::post('/get_single_clients',   'Adm\Tacks\PagesController@getSingleClients')->name('tack.common.get_single_clients');
Route::post('/get_region_name',   'Adm\Tacks\PagesController@getRegionName')->name('tack.common.get_region_name');
Route::post('/get_single_mail_clients', 'Adm\Tacks\PagesController@getSingleMailClients')->name('tack.common.get_single_mail_clients');
Route::post('/get_roles_group',         'Adm\Tacks\PagesController@getRolesGroup')->name('tack.common.get_roles_group');

Route::post('/check_user_for_id',    'Adm\Tacks\PagesController@checkUserForId')->name('tack.common.check_user_for_id');
Route::post('/check_user_for_login', 'Adm\Tacks\PagesController@checkUserForLogin')->name('tack.common.check_user_for_login');
Route::post('/task_has_operator',    'Adm\Tacks\PagesController@taskHasOperator')->name('tack.common.task_has_operator');

Route::post('/ch_data_complete',    'Adm\Tacks\PagesController@chDataComplete')->name('tack.common.ch_data_complete');
Route::post('/ch_data_work_stage',    'Adm\Tacks\PagesController@chDataWorkStage')->name('tack.common.ch_data_work_stage');
Route::post('/ch_response_complete',    'Adm\Tacks\PagesController@chResponseComplete')->name('tack.common.ch_response_complete');
Route::post('/add_mess',            'Adm\Tacks\PagesController@addTaskMess')->name('tack.common.add_mess');
Route::post('/get_mess',            'Adm\Tacks\PagesController@getTaskMess')->name('tack.common.get_mess');


Route::post('/pong_operator',        'Adm\Users\PostAjaxController@pongOperator')->name('users.common.pong_operator');
Route::post('/pong_operator_out',    'Adm\Users\PostAjaxController@pongOperatorOut')->name('users.common.pong_operator_out');


Route::get('/set_password_1234546',     'Auth\LoginController@setPassword1234546')->name('tack.common.setPassword1234546');
