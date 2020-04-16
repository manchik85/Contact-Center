<?php

namespace App\Http\Controllers\Adm\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\userAcauntPostAdm;
use App\Models\Adm\Tacks;
use App\Models\Adm\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Cache;
use Auth;
use function React\Promise\reject;

class PagesController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function accessPages()
  {
    $data['user'] = $this->request->user();

    $levels = Users::getAccessList();
    $user_group = Users::getGrouAccessList($data);

    foreach ($levels AS $_k => $_levels) {
      $data['access']['permissionss'][$_levels->user_permissions_group_name][$_k]['permissions_title'] = $_levels->user_permissions_title;
      $data['access']['permissionss'][$_levels->user_permissions_group_name][$_k]['permissions_id_c'] = Crypt::encryptString($_levels->user_permissions_id);
      $data['access']['permissionss'][$_levels->user_permissions_group_name][$_k]['permissions_id'] = $_levels->user_permissions_id;
      $data['access']['permissionss'][$_levels->user_permissions_group_name][$_k]['permissions_comment'] = $_levels->user_permissions_comment;
      $data['access']['permissionss'][$_levels->user_permissions_group_name][$_k]['user_permissions_group_id'] = $_levels->user_permissions_group_id;
      $data['access']['permissionss'][$_levels->user_permissions_group_name][$_k]['user_permissions_group_name'] = $_levels->user_permissions_group_name;
    }
    foreach ($user_group AS $group) {
      $data['access']['users'][$group->id]['id'] = Crypt::encryptString($group->id);
      $data['access']['users'][$group->id]['group'] = $group->group;
      $data['access']['users'][$group->id]['permissions'][$group->permissions] = $group->permissions;
    }


    return view('admin.users.adm_access', $data);
  }

  public function usersList()
  {
    $data = [];
    $usersList = [];
    $users = Users::getUsersList();

    foreach ($users AS $_u) {
      $usersList[$_u->status]['group'] = $_u->group;
      $usersList[$_u->status][$_u->id]['date'] = date('d.m.Y', strtotime($_u->created_at));
      $usersList[$_u->status][$_u->id]['time'] = date('H:i:s', strtotime($_u->created_at));
      $usersList[$_u->status][$_u->id]['name'] = $_u->name;
      $usersList[$_u->status][$_u->id]['status'] = $_u->status;
      $usersList[$_u->status][$_u->id]['email'] = $_u->email;
      $usersList[$_u->status][$_u->id]['phone'] = $_u->users_phone;
      $usersList[$_u->status][$_u->id]['online'] = Users::isOnline($_u->id);
      $usersList[$_u->status][$_u->id]['online_in_phone'] = false;//Users::isOnlineInPhone($_u->users_phone);

    }

    $data['usersList'] = $usersList;

    return view('admin.users.list_users', $data);
  }

  public function whiteIP()
  {
    $list = Users::getIpList();
    return view('admin.users.white_ip', compact(['list']));
  }

  public function usersProfile(userAcauntPostAdm $request)
  {
    $this->request = $request;
    $data = [];
    if (!is_null($this->request->input('mod')) && $this->request->input('mod') > 0) {

      $data['user_id'] = $this->request->input('users_id');
      $data['users_name'] = $this->request->input('name');
      $data['users_email'] = $this->request->input('email');
      $data['users_phone'] = $this->request->input('users_phone');

      $data['gov_group'] = $this->request->input('gov_group');
      $data['gov_group_master'] = $this->request->input('gov_group_master');
      $data['gov_group_root'] = $this->request->input('gov_group_root');

      $data['users_cont_phone'] = $this->request->input('users_cont_phone');

      if ($data['users_name'] != false && $data['users_email'] != false && (int)$data['users_phone'] >= 4000) {
        Users::admChengeCommonInfo($data);
      }
    }
    $data['dataUser'] = Users::getUserAllInfo($this->request->input('users_id'));
    return view('admin.users.users_profile_pages', $data);
  }

  public function userStatisticPage(userAcauntPostAdm $request)
  {
    $this->request = $request;
    $stat['data'] = [];
    $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
    $users_end_statistic = date('d.m.Y');

    // users_id
    if ($this->request->input('users_id')) {
      $users_id = $this->request->input('users_id');
      Cache::forget('users_id');
      Cache::forever('users_id', $users_id);
    } else if (Cache::has('users_id')) {
      $users_id = Cache::get('users_id');
    } else {
      redirect('/list_users');
    }

    // start
    if ($this->request->input('start') && $this->request->input('start') != '') {
      $users_start_statistic = $this->request->input('start');
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    } else if (Cache::has('users_start_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_start_statistic = Cache::get('users_start_statistic' . auth()->id());
    } else {
      $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    }

    // end
    if ($this->request->input('end') && $this->request->input('end') != '') {
      $users_end_statistic = $this->request->input('end');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    } else if (Cache::has('users_end_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_end_statistic' . auth()->id());
    } else {
      $users_end_statistic = date('d.m.Y');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    }

    $stat['users_id'] = $users_id;
    $stat['start'] = $users_start_statistic;
    $stat['end'] = $users_end_statistic;

    $stat['user'] = Users::getClientDataADM($users_id);
    $stat['data'] = Users::getStatisticData($stat);
    $stat['date'] = [];
    $stat['sigma_on'] = 0;
    $stat['sigma_aut'] = 0;
    $stat['sigma_off'] = 0;

    if (count($stat['data']) > 0) {

      foreach ($stat['data'] AS $_k => $_v) {

        $stat['date'][$_k]['id'] = $_v->id;
        $stat['date'][$_k]['common_date'] = strtotime($_v->user_time);
        $stat['date'][$_k]['user_event'] = $_v->user_event;
        $stat['date'][$_k]['out_desc'] = $_v->out_desc;
        $stat['date'][$_k]['agent'] = $_v->agent;

        if ($_k > 0) {
          $stat['date'][$_k]['delta'] = $stat['date'][$_k - 1]['common_date'] - strtotime($_v->user_time);
        } else {
          $stat['date'][$_k]['delta'] = 0;
        }

        if ($_v->user_event == 'online') {
          $stat['sigma_on'] += $stat['date'][$_k]['delta'];
        } else if ($_v->user_event == 'aut') {
          $stat['sigma_aut'] += $stat['date'][$_k]['delta'];
        } else if ($_v->user_event == 'offline' || $_v->user_event == 'error') {
          $stat['sigma_off'] += $stat['date'][$_k]['delta'];
        }

      }
    }

    $stat['sigma_on'] = $this->dataFormater($stat['sigma_on']);
    $stat['sigma_aut'] = $this->dataFormater($stat['sigma_aut']);
    $stat['sigma_off'] = $this->dataFormater($stat['sigma_off']);

    return view('admin.users.users_statistic_pages', $stat);
  }

  public function statisticExel(userAcauntPostAdm $request)
  {
    $this->request = $request;
    $stat['data'] = [];
    $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
    $users_end_statistic = date('d.m.Y');

    // users_id
    if ($this->request->input('users_id')) {
      $users_id = $this->request->input('users_id');
      Cache::forget('users_id');
      Cache::forever('users_id', $users_id);
    } else if (Cache::has('users_id')) {
      $users_id = Cache::get('users_id');
    } else {
      redirect('/list_users');
    }

    // start
    if ($this->request->input('start') && $this->request->input('start') != '') {
      $users_start_statistic = $this->request->input('start');
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    } else if (Cache::has('users_start_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_start_statistic = Cache::get('users_start_statistic' . auth()->id());
    } else {
      $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    }

    // end
    if ($this->request->input('end') && $this->request->input('end') != '') {
      $users_end_statistic = $this->request->input('end');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    } else if (Cache::has('users_end_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_end_statistic' . auth()->id());
    } else {
      $users_end_statistic = date('d.m.Y');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    }

    $stat['users_id'] = $users_id;
    $stat['start'] = $users_start_statistic;
    $stat['end'] = $users_end_statistic;

    $stat['user'] = Users::getClientDataADM($users_id);
    $stat['data'] = Users::getStatisticData($stat);
    $stat['date'] = [];
    $stat['sigma_on'] = 0;
    $stat['sigma_aut'] = 0;
    $stat['sigma_off'] = 0;


    if (count($stat['data']) > 0) {

      foreach ($stat['data'] AS $_k => $_v) {

        $stat['date'][$_k]['id'] = $_v->id;
        $stat['date'][$_k]['common_date'] = strtotime($_v->user_time);
        $stat['date'][$_k]['user_event'] = $_v->user_event;
        $stat['date'][$_k]['out_desc'] = $_v->out_desc;

        if ($_k > 0) {
          $stat['date'][$_k]['delta'] = $stat['date'][$_k - 1]['common_date'] - strtotime($_v->user_time);
        } else {
          $stat['date'][$_k]['delta'] = 0;
        }

        if ($_v->user_event == 'online') {
          $stat['sigma_on'] += $stat['date'][$_k]['delta'];
        } else if ($_v->user_event == 'aut') {
          $stat['sigma_aut'] += $stat['date'][$_k]['delta'];
        } else if ($_v->user_event == 'offline' || $_v->user_event == 'error') {
          $stat['sigma_off'] += $stat['date'][$_k]['delta'];
        }

      }
    }


    $stat['sigma_on'] = $this->dataFormater($stat['sigma_on']);
    $stat['sigma_aut'] = $this->dataFormater($stat['sigma_aut']);
    $stat['sigma_off'] = $this->dataFormater($stat['sigma_off']);

    return Excel::download(new ExelCancelExport($stat), 'Статистика пользователя ' . $stat['user'][0]->name . ' за период: ' . $users_start_statistic . ' - ' . $users_end_statistic . '.xlsx');

  }

  public function usersAdd()
  {
    $data = [];
    return view('admin.users.users_add', $data);
  }

  public function autPage()
  {
    $data = [];

    if (Auth::check()) {

      return view('admin.users.aut_page', $data);
    } else {
      return redirect('/login');
    }

  }

  public function listOperatorsStatistic(userAcauntPostAdm $request)
  {
    $this->request = $request;
    $stat['date'] = [];
    $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
    $users_end_statistic = date('d.m.Y');

    $sigma_on = 0;
    $sigma_aut = 0;
    $sigma_off = 0;

    $stat['calls'] = 0;
    $stat['answered'] = 0;
    $stat['no_answered'] = 0;
    $stat['advice_tack'] = 0;
    $stat['request_tack'] = 0;

    // start
    if ($this->request->input('start') && $this->request->input('start') != '') {
      $users_start_statistic = $this->request->input('start');
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    } else if (Cache::has('users_start_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_start_statistic = Cache::get('users_start_statistic' . auth()->id());
    } else {
      $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    }

    // end
    if ($this->request->input('end') && $this->request->input('end') != '') {
      $users_end_statistic = $this->request->input('end');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    } else if (Cache::has('users_end_statistic') && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_end_statistic' . auth()->id());
    } else {
      $users_end_statistic = date('d.m.Y');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    }

    // gov_group
    if ($this->request->input('gov_group') && $this->request->input('gov_group') != '') {
      $users_gov_group_statistic = $this->request->input('gov_group');
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    } else if (Cache::has('users_gov_group_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_gov_group_statistic' . auth()->id());
    } else {
      $users_gov_group_statistic = '';
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    }

    $stat['start'] = $users_start_statistic;
    $stat['end'] = $users_end_statistic;
    $data_statist = Users::getAllStatisticUsers(5, $users_gov_group_statistic);

    if (count($data_statist) > 0) {
      foreach ($data_statist AS $_kk => $_vv) {

        $stat['users_id'] = $_vv->users_id;
        $statistic = Users::getAllStatisticData($stat);
        $asterisk = Users::getAsteriskStatistic($_vv->users_phone, $users_start_statistic, $users_end_statistic);
        $calls = 0;
        $answered = 0;
        $no_answered = 0;

        $stat['date'][$_vv->users_id]['id'] = $_vv->users_id;
        $stat['date'][$_vv->users_id]['name'] = $_vv->name;
        $stat['date'][$_vv->users_id]['email'] = $_vv->email;
        $stat['date'][$_vv->users_id]['users_phone'] = $_vv->users_phone;
        $stat['date'][$_vv->users_id]['gov_group'] = $_vv->gov_group;
        $stat['date'][$_vv->users_id]['gov_group_master'] = $_vv->gov_group_master;
        $stat['date'][$_vv->users_id]['gov_group_root'] = $_vv->gov_group_root;

        $stat['date'][$_vv->users_id]['users_cont_phone'] = $_vv->users_cont_phone;

        $stat['date'][$_vv->users_id]['sigma_on'] = 0;
        $stat['date'][$_vv->users_id]['sigma_aut'] = 0;
        $stat['date'][$_vv->users_id]['sigma_off'] = 0;

        $stat['date'][$_vv->users_id]['advice_tack'] = Users::getUserCons($_vv->users_id, $users_start_statistic, $users_end_statistic, 'advice_tack');
        $stat['date'][$_vv->users_id]['request_tack'] = Users::getUserCons($_vv->users_id, $users_start_statistic, $users_end_statistic, 'request_tack');

        $stat['advice_tack'] += $stat['date'][$_vv->users_id]['advice_tack'];
        $stat['request_tack'] += $stat['date'][$_vv->users_id]['request_tack'];

        if (count($statistic) > 0) {
          foreach ($statistic AS $_k => $_v) {

            $statdate[$_k]['common_date'] = strtotime($_v->user_time);

            if ($_k > 0) {
              $statdate[$_k]['delta'] = $statdate[$_k - 1]['common_date'] - strtotime($_v->user_time);
            } else {
              $statdate[$_k]['delta'] = 0;
            }

            if ($_v->user_event == 'online') {
              $stat['date'][$_vv->users_id]['sigma_on'] += $statdate[$_k]['delta'];
              $sigma_on += $statdate[$_k]['delta'];
            } else if ($_v->user_event == 'aut') {
              $stat['date'][$_vv->users_id]['sigma_aut'] += $statdate[$_k]['delta'];
              $sigma_aut += $statdate[$_k]['delta'];
            } else if ($_v->user_event == 'offline' || $_v->user_event == 'error') {
              $stat['date'][$_vv->users_id]['sigma_off'] += $statdate[$_k]['delta'];
              $sigma_off += $statdate[$_k]['delta'];
            }

          }
        }
        if (count($asterisk) > 0) {
          foreach ($asterisk AS $_k => $_v) {
            $calls++;
            if ($_v->disposition == 'ANSWERED') {
              $answered++;
            }
            if ($_v->disposition == 'NO ANSWER') {
              $no_answered++;
            }
          }
        }
        $stat['calls'] += $calls;
        $stat['answered'] += $answered;
        $stat['no_answered'] += $no_answered;
        $stat['date'][$_vv->users_id]['calls'] = $calls;
        $stat['date'][$_vv->users_id]['answered'] = $answered;
        $stat['date'][$_vv->users_id]['no_answered'] = $no_answered;
        $stat['date'][$_vv->users_id]['sigma_on'] = $this->dataFormater($stat['date'][$_vv->users_id]['sigma_on']);
        $stat['date'][$_vv->users_id]['sigma_aut'] = $this->dataFormater($stat['date'][$_vv->users_id]['sigma_aut']);
        $stat['date'][$_vv->users_id]['sigma_off'] = $this->dataFormater($stat['date'][$_vv->users_id]['sigma_off']);
      }
    }

    $stat['sigma_on']     = $this->dataFormater($sigma_on);
    $stat['sigma_aut']    = $this->dataFormater($sigma_aut);
    $stat['sigma_off']    = $this->dataFormater($sigma_off);
    $stat['sigma_all']    = $this->dataFormater($sigma_off+$sigma_on+$sigma_on);


    $stat['sigma_on_sou']     =  $sigma_on;
    $stat['sigma_aut_sou']    =  $sigma_aut;
    $stat['sigma_off_sou']    =  $sigma_off;
    $stat['roles_office'] = Users::getGovGroup();

    //add call statistic
    $callsStatistic = Users::getAsteriskCallStatistic($users_start_statistic, $users_end_statistic);
    $stat['call_st_quantity'] = $callsStatistic[0]->quantity;
    if($callsStatistic[0]->quantity == 0){
      $stat['call_st_answered'] = 0;
      $stat['call_st_missed'] = 0;
      $stat['call_st_waitAverageTime'] = 0;
    }else{
      $stat['call_st_answered'] = $callsStatistic[0]->quantity - $callsStatistic[0]->missed;
      $stat['call_st_missed'] = $callsStatistic[0]->missed;
      $stat['call_st_waitAverageTime'] = $this->dataFormater(number_format($callsStatistic[0]->waitAverageTime, 2));
    }
    //

    return view('admin.users.list_operators_statistic', $stat);
  }

  public function listOperatorsStatisticExell(userAcauntPostAdm $request)
  {
    $this->request = $request;
    $stat['date'] = [];
    $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
    $users_end_statistic = date('d.m.Y');

    $sigma_on = 0;
    $sigma_aut = 0;
    $sigma_off = 0;

    $stat['calls'] = 0;
    $stat['answered'] = 0;
    $stat['no_answered'] = 0;
    $stat['advice_tack'] = 0;
    $stat['request_tack'] = 0;

    // start
    if ($this->request->input('start') && $this->request->input('start') != '') {
      $users_start_statistic = $this->request->input('start');
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    } else if (Cache::has('users_start_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_start_statistic = Cache::get('users_start_statistic' . auth()->id());
    } else {
      $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    }

    // end
    if ($this->request->input('end') && $this->request->input('end') != '') {
      $users_end_statistic = $this->request->input('end');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    } else if (Cache::has('users_end_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_end_statistic' . auth()->id());
    } else {
      $users_end_statistic = date('d.m.Y');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    }

    // gov_group
    if ($this->request->input('gov_group') && $this->request->input('gov_group') != '') {
      $users_gov_group_statistic = $this->request->input('gov_group');
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    } else if (Cache::has('users_gov_group_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_gov_group_statistic' . auth()->id());
    } else {
      $users_gov_group_statistic = '';
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    }

    $stat['start'] = $users_start_statistic;
    $stat['end'] = $users_end_statistic;

    $data_statist = Users::getAllStatisticUsers(5, $users_gov_group_statistic);

    if (count($data_statist) > 0) {
      foreach ($data_statist AS $_kk => $_vv) {

        $stat['users_id'] = $_vv->users_id;
        $statistic = Users::getAllStatisticData($stat);
        $asterisk = Users::getAsteriskStatistic($_vv->users_phone, $users_start_statistic, $users_end_statistic);
        $calls = 0;
        $answered = 0;
        $no_answered = 0;

        $stat['date'][$_vv->users_id]['id'] = $_vv->users_id;
        $stat['date'][$_vv->users_id]['name'] = $_vv->name;
        $stat['date'][$_vv->users_id]['email'] = $_vv->email;
        $stat['date'][$_vv->users_id]['users_phone'] = $_vv->users_phone;
        $stat['date'][$_vv->users_id]['gov_group'] = $_vv->gov_group;
        $stat['date'][$_vv->users_id]['gov_group_master'] = $_vv->gov_group_master;
        $stat['date'][$_vv->users_id]['gov_group_root'] = $_vv->gov_group_root;

        $stat['date'][$_vv->users_id]['users_cont_phone'] = $_vv->users_cont_phone;

        $stat['date'][$_vv->users_id]['sigma_on'] = 0;
        $stat['date'][$_vv->users_id]['sigma_aut'] = 0;
        $stat['date'][$_vv->users_id]['sigma_off'] = 0;

        $stat['date'][$_vv->users_id]['advice_tack'] = Users::getUserCons($_vv->users_id, $users_start_statistic, $users_end_statistic, 'advice_tack');
        $stat['date'][$_vv->users_id]['request_tack'] = Users::getUserCons($_vv->users_id, $users_start_statistic, $users_end_statistic, 'request_tack');

        $stat['advice_tack'] += $stat['date'][$_vv->users_id]['advice_tack'];
        $stat['request_tack'] += $stat['date'][$_vv->users_id]['request_tack'];

        if (count($statistic) > 0) {
          foreach ($statistic AS $_k => $_v) {

            $statdate[$_k]['common_date'] = strtotime($_v->user_time);

            if ($_k > 0) {
              $statdate[$_k]['delta'] = $statdate[$_k - 1]['common_date'] - strtotime($_v->user_time);
            } else {
              $statdate[$_k]['delta'] = 0;
            }

            if ($_v->user_event == 'online') {
              $stat['date'][$_vv->users_id]['sigma_on'] += $statdate[$_k]['delta'];
              $sigma_on += $statdate[$_k]['delta'];
            } else if ($_v->user_event == 'aut') {
              $stat['date'][$_vv->users_id]['sigma_aut'] += $statdate[$_k]['delta'];
              $sigma_aut += $statdate[$_k]['delta'];
            } else if ($_v->user_event == 'offline' || $_v->user_event == 'error') {
              $stat['date'][$_vv->users_id]['sigma_off'] += $statdate[$_k]['delta'];
              $sigma_off += $statdate[$_k]['delta'];
            }


          }
        }
        if (count($asterisk) > 0) {
          foreach ($asterisk AS $_k => $_v) {
            $calls++;
            if ($_v->disposition == 'ANSWERED') {
              $answered++;
            }
            if ($_v->disposition == 'NO ANSWER') {
              $no_answered++;
            }
          }
        }
        $stat['calls'] += $calls;
        $stat['answered'] += $answered;
        $stat['no_answered'] += $no_answered;
        $stat['date'][$_vv->users_id]['calls'] = $calls;
        $stat['date'][$_vv->users_id]['answered'] = $answered;
        $stat['date'][$_vv->users_id]['no_answered'] = $no_answered;
        $stat['date'][$_vv->users_id]['sigma_on'] = $this->dataFormater($stat['date'][$_vv->users_id]['sigma_on']);
        $stat['date'][$_vv->users_id]['sigma_aut'] = $this->dataFormater($stat['date'][$_vv->users_id]['sigma_aut']);
        $stat['date'][$_vv->users_id]['sigma_off'] = $this->dataFormater($stat['date'][$_vv->users_id]['sigma_off']);
      }
    }

    $stat['sigma_on'] = $this->dataFormater($sigma_on);
    $stat['sigma_aut'] = $this->dataFormater($sigma_aut);
    $stat['sigma_off'] = $this->dataFormater($sigma_off);

    //add call statistic
    $callsStatistic = Users::getAsteriskCallStatistic($users_start_statistic, $users_end_statistic);
    $stat['call_st_quantity'] = $callsStatistic[0]->quantity;
    if($callsStatistic[0]->quantity == 0){
      $stat['call_st_answered'] = 0;
      $stat['call_st_missed'] = 0;
      $stat['call_st_waitAverageTime'] = 0;
    }else{
      $stat['call_st_answered'] = $callsStatistic[0]->quantity - $callsStatistic[0]->missed;
      $stat['call_st_missed'] = $callsStatistic[0]->missed;
      $stat['call_st_waitAverageTime'] = $this->dataFormater(number_format($callsStatistic[0]->waitAverageTime, 2));
    }//

    //dd($stat);
    return Excel::download(new ExelStatisticExport($stat), 'Статистика работы Операторов за период: ' . $users_start_statistic . ' - ' . $users_end_statistic . '.xlsx');

  }

  public function kpiPages(userAcauntPostAdm $request)
  {
    $this->request = $request;
    $stat['date'] = [];
    $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
    $users_end_statistic = date('d.m.Y');

    $sigma_on = 0;
    $sigma_aut = 0;
    $sigma_off = 0;

    $stat['calls'] = 0;
    $stat['answered'] = 0;
    $stat['no_answered'] = 0;
    $stat['advice_tack'] = 0;
    $stat['request_tack'] = 0;

    // start
    if ($this->request->input('start') && $this->request->input('start') != '') {
      $users_start_statistic = $this->request->input('start');
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    } else if (Cache::has('users_start_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_start_statistic = Cache::get('users_start_statistic' . auth()->id());
    } else {
      $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    }

    // end
    if ($this->request->input('end') && $this->request->input('end') != '') {
      $users_end_statistic = $this->request->input('end');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic', $users_end_statistic);
    } else if (Cache::has('users_end_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_end_statistic' . auth()->id());
    } else {
      $users_end_statistic = date('d.m.Y');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    }

    // gov_group
    if ($this->request->input('gov_group') && $this->request->input('gov_group') != '') {
      $users_gov_group_statistic = $this->request->input('gov_group');
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    } else if (Cache::has('users_gov_group_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_gov_group_statistic' . auth()->id());
    } else {
      $users_gov_group_statistic = '';
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    }

    $stat['start'] = $users_start_statistic;
    $stat['end'] = $users_end_statistic;
    $data_statist = Users::getAllStatisticUsers(5, $users_gov_group_statistic);


    if (count($data_statist) > 0) {

      $date_start_sql = $this->dateSQL($users_start_statistic) . ' 00:00:00';
      $date_stop_sql = $this->dateSQL($users_end_statistic) . ' 23:59:59';

      foreach ($data_statist AS $_kk => $_vv) {

        $stat['users_id'] = $_vv->users_id;
        $statistic = Users::getAllStatisticData($stat);

        $stat['date'][$_vv->users_id]['kpi'] = 0;
        $stat['date'][$_vv->users_id]['id'] = $_vv->users_id;
        $stat['date'][$_vv->users_id]['name'] = $_vv->name;
        $stat['date'][$_vv->users_id]['email'] = $_vv->email;
        $stat['date'][$_vv->users_id]['users_phone'] = $_vv->users_phone;
        $stat['date'][$_vv->users_id]['gov_group'] = $_vv->gov_group;
        $stat['date'][$_vv->users_id]['gov_group_master'] = $_vv->gov_group_master;
        $stat['date'][$_vv->users_id]['gov_group_root'] = $_vv->gov_group_root;
        $stat['date'][$_vv->users_id]['users_cont_phone'] = $_vv->users_cont_phone;

        $period = (strtotime($date_stop_sql) - strtotime($date_start_sql)) / 60 / 60 / 24;

        for ($_i = 0; $_i <= $period; $_i++) {

          $kef = 0;

          $dat_top = strtotime($date_start_sql) + ($_i * 24 * 60 * 60);
          $dat_pop = strtotime($date_start_sql) + (($_i + 1) * 24 * 60 * 60);

          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_enter_mor'] = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time']  = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_marks']     = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_all']  = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_off']  = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi']           = 0;
          $stat[date('Y-m-d', $dat_top)]['common_date']   = [];


          // один такт статистики
          if (count($statistic) > 0) {
            foreach ($statistic AS $_k => $_v) {

              // формируем дни
              if (strtotime($_v->user_time) > $dat_top &&
                strtotime($_v->user_time) <= $dat_pop) {

                $morning = strtotime(date('Y-m-d 09:00:00', $dat_top));
                $evening = strtotime(date('Y-m-d 23:59:59', $dat_top));

                // смотрим вовремя ли пришёл на работу
                if (strtotime($_v->user_time) <= $morning && $_v->user_event == 'online') {
                  $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_enter_mor'] = 1;
                }
                $stat[date('Y-m-d', $dat_top)]['common_date'][] = $_v;
              }
            }
          }

          $common_date = $stat[date('Y-m-d', $dat_top)]['common_date'];
          if (count($common_date) > 0) {
            foreach ($common_date AS $_k => $_v) {

              $statdate[$_k]['common_date'] = strtotime($_v->user_time);

              if ($_k > 0) {
                $statdate[$_k]['delta'] = $statdate[$_k - 1]['common_date'] - strtotime($_v->user_time);
              } else {
                $statdate[$_k]['delta'] = 0;
              }

              // Считаем сколько всего часов работал
              if ($_v->user_event == 'online') {
                $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time'] += $statdate[$_k]['delta'];
              }
            }
          }
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time'] = round(($stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time'] / 3600) , 2);

          if($stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time']>0){

            // получаем оценки
            $result_mark = 0;
            $kpi_marks = Users::getAsteriskMarks( $_vv->users_phone, date('Y-m-d 09:00:00', $dat_top),  date('Y-m-d 23:59:59', $dat_top) );
            if( count($kpi_marks)>0 ) {
              foreach( $kpi_marks AS $mark ){
                $result_mark +=$mark->valuation;
              }
              $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_marks'] = round(($result_mark/count($kpi_marks)), 2);
            }

            // получаем кол-во звонков
            $kpi_dial_off = 0;
            $kpi_dial_on  = 0;
            $kpi_dial_all = Users::getAsterisDial( $_vv->users_phone, date('Y-m-d 09:00:00', $dat_top),  date('Y-m-d 23:59:59', $dat_top) );
            if( count($kpi_dial_all)>0 ) {
              foreach( $kpi_dial_all AS $dial ){
                if($dial->disposition=='NO ANSWER'){
                  $kpi_dial_off ++;
                }
                if($dial->disposition!='BUSY'){
                  $kpi_dial_on ++;
                }
              }

              if( ($kpi_dial_on - $kpi_dial_off)<=0){
                $kef = 0;
              }
              else{
                $kef = $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time']+($kpi_dial_on - $kpi_dial_off);
                $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_all']  = $kpi_dial_on;
                $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_off']  = $kpi_dial_off;
              }
            }

          }

           // KPI по дням
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi'] = round(
            $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_enter_mor'] +
            $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_marks'] +
            $kef, 2);

          $stat['date'][$_vv->users_id]['kpi'] += $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi'];

        }
      }

    }

    $stat['roles_office'] = Users::getGovGroup();

    return view('admin.users.kpi_page', $stat);
  }

  public function kpiPagesExcel(userAcauntPostAdm $request)
  {
    $this->request = $request;
    $stat['date'] = [];
    $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
    $users_end_statistic = date('d.m.Y');

    $sigma_on = 0;
    $sigma_aut = 0;
    $sigma_off = 0;

    $stat['calls'] = 0;
    $stat['answered'] = 0;
    $stat['no_answered'] = 0;
    $stat['advice_tack'] = 0;
    $stat['request_tack'] = 0;

    // start
    if ($this->request->input('start') && $this->request->input('start') != '') {
      $users_start_statistic = $this->request->input('start');
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    } else if (Cache::has('users_start_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_start_statistic = Cache::get('users_start_statistic' . auth()->id());
    } else {
      $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
      Cache::forget('users_start_statistic' . auth()->id());
      Cache::forever('users_start_statistic' . auth()->id(), $users_start_statistic);
    }

    // end
    if ($this->request->input('end') && $this->request->input('end') != '') {
      $users_end_statistic = $this->request->input('end');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic', $users_end_statistic);
    } else if (Cache::has('users_end_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_end_statistic' . auth()->id());
    } else {
      $users_end_statistic = date('d.m.Y');
      Cache::forget('users_end_statistic' . auth()->id());
      Cache::forever('users_end_statistic' . auth()->id(), $users_end_statistic);
    }

    // gov_group
    if ($this->request->input('gov_group') && $this->request->input('gov_group') != '') {
      $users_gov_group_statistic = $this->request->input('gov_group');
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    } else if (Cache::has('users_gov_group_statistic' . auth()->id()) && $this->request->input('page') != '') {
      $users_end_statistic = Cache::get('users_gov_group_statistic' . auth()->id());
    } else {
      $users_gov_group_statistic = '';
      Cache::forget('users_gov_group_statistic' . auth()->id());
      Cache::forever('users_gov_group_statistic' . auth()->id(), $users_gov_group_statistic);
    }

    $stat['start'] = $users_start_statistic;
    $stat['end'] = $users_end_statistic;
    $data_statist = Users::getAllStatisticUsers(5, $users_gov_group_statistic);

    if (count($data_statist) > 0) {
      $date_start_sql = $this->dateSQL($users_start_statistic) . ' 00:00:00';
      $date_stop_sql = $this->dateSQL($users_end_statistic) . ' 23:59:59';

      foreach ($data_statist AS $_kk => $_vv) {

        $stat['users_id'] = $_vv->users_id;
        $statistic = Users::getAllStatisticData($stat);

        $stat['date'][$_vv->users_id]['kpi'] = 0;
        $stat['date'][$_vv->users_id]['id'] = $_vv->users_id;
        $stat['date'][$_vv->users_id]['name'] = $_vv->name;
        $stat['date'][$_vv->users_id]['email'] = $_vv->email;
        $stat['date'][$_vv->users_id]['users_phone'] = $_vv->users_phone;
        $stat['date'][$_vv->users_id]['gov_group'] = $_vv->gov_group;
        $stat['date'][$_vv->users_id]['gov_group_master'] = $_vv->gov_group_master;
        $stat['date'][$_vv->users_id]['gov_group_root'] = $_vv->gov_group_root;
        $stat['date'][$_vv->users_id]['users_cont_phone'] = $_vv->users_cont_phone;

        $period = (strtotime($date_stop_sql) - strtotime($date_start_sql)) / 60 / 60 / 24;

        for ($_i = 0; $_i <= $period; $_i++) {

          $kef = 0;

          $dat_top = strtotime($date_start_sql) + ($_i * 24 * 60 * 60);
          $dat_pop = strtotime($date_start_sql) + (($_i + 1) * 24 * 60 * 60);

          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_enter_mor'] = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time']  = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_marks']     = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_all']  = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_off']  = 0;
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi']           = 0;
          $stat[date('Y-m-d', $dat_top)]['common_date']   = [];


          // один такт статистики
          if (count($statistic) > 0) {
            foreach ($statistic AS $_k => $_v) {

              // формируем дни
              if (strtotime($_v->user_time) > $dat_top &&
                strtotime($_v->user_time) <= $dat_pop) {

                $morning = strtotime(date('Y-m-d 09:00:00', $dat_top));
                $evening = strtotime(date('Y-m-d 23:59:59', $dat_top));

                // смотрим вовремя ли пришёл на работу
                if (strtotime($_v->user_time) <= $morning && $_v->user_event == 'online') {
                  $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_enter_mor'] = 1;
                }
                $stat[date('Y-m-d', $dat_top)]['common_date'][] = $_v;
              }
            }
          }

          $common_date = $stat[date('Y-m-d', $dat_top)]['common_date'];
          if (count($common_date) > 0) {
            foreach ($common_date AS $_k => $_v) {

              $statdate[$_k]['common_date'] = strtotime($_v->user_time);

              if ($_k > 0) {
                $statdate[$_k]['delta'] = $statdate[$_k - 1]['common_date'] - strtotime($_v->user_time);
              } else {
                $statdate[$_k]['delta'] = 0;
              }

              // Считаем сколько всего часов работал
              if ($_v->user_event == 'online') {
                $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time'] += $statdate[$_k]['delta'];
              }
            }
          }
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time'] = round(($stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time'] / 3600) , 2);

          if($stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time']>0){

            // получаем оценки
            $result_mark = 0;
            $kpi_marks = Users::getAsteriskMarks( $_vv->users_phone, date('Y-m-d 09:00:00', $dat_top),  date('Y-m-d 23:59:59', $dat_top) );
            if( count($kpi_marks)>0 ) {
              foreach( $kpi_marks AS $mark ){
                $result_mark +=$mark->valuation;
              }
              $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_marks'] = round(($result_mark/count($kpi_marks)), 2);
            }

            // получаем кол-во звонков
            $kpi_dial_off = 0;
            $kpi_dial_on  = 0;
            $kpi_dial_all = Users::getAsterisDial( $_vv->users_phone, date('Y-m-d 09:00:00', $dat_top),  date('Y-m-d 23:59:59', $dat_top) );
            if( count($kpi_dial_all)>0 ) {
              foreach( $kpi_dial_all AS $dial ){
                if($dial->disposition=='NO ANSWER'){
                  $kpi_dial_off ++;
                }
                if($dial->disposition!='BUSY'){
                  $kpi_dial_on ++;
                }
              }

              if( ($kpi_dial_on - $kpi_dial_off)<=0){
                $kef = 0;
              }
              else{
                $kef = $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_all_time']+($kpi_dial_on - $kpi_dial_off);
                $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_all']  = $kpi_dial_on;
                $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_dial_off']  = $kpi_dial_off;
              }
            }

          }

           // KPI по дням
          $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi'] = round(
            $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_enter_mor'] +
            $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi_marks']     +
            $kef, 2);

          $stat['date'][$_vv->users_id]['kpi'] += $stat['date'][$_vv->users_id]['dais'][date('Y-m-d', $dat_top)]['kpi'];

        }
      }
    }

    return Excel::download(new ExelKPIExport($stat), 'KPI операторов за период: ' . $users_start_statistic . ' - ' . $users_end_statistic . '.xlsx');
  }

  private function dateSQL($date)
  {
    $start_arr = explode('.', $date);
    return $start_arr[2] . '-' . $start_arr[1] . '-' . $start_arr[0];
  }

  private function dataFormater($count_sec)
  {
    return sprintf('%02d:%02d:%02d', $count_sec / 3600, ($count_sec % 3600) / 60, ($count_sec % 3600) % 60);
  }

}
