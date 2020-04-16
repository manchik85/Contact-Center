<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/login',
        '/users_profile',
        '/user_statistic_page',
        '/profile_update',
        '/tack_add_page',
        '/dashboard',
        '/broadcasting/auth',
        '/tack_cons_page',
        '/deal_list_page',
        '/my_tack_page_operator',
        '/my_tack_page_developer',
        '/task_edit_developer',
        '/tack_list_page',
        '/cons_list_page',
        '/upload_additions',
        '/list_operators_statistic',
        '/task_edit',
        '/close_tack_operator',
        '/unic_email',
        '/add_users',
        '/call_list_page',
        '/call_user_page',
        '/notice_user_page',
        '/gov_group_add',
        '/gov_group_del',
        '/send_usermail',
        '/del_mail',
        '/unit_mail_page',
        '/process_names',
        '/name_group_del',
        '/prior_days',
        '/kpi_page',
    ];
}
