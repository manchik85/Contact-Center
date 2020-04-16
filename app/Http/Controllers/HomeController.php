<?php

namespace App\Http\Controllers;

use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Adm\Tacks;

class HomeController extends Controller
{
  protected $request;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(Request $request)
  {
    $this->middleware('auth');
    $this->request = $request;
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    // start
    if ($this->request->input('start') && $this->request->input('start') != '') {
      $users_start_statistic = $this->request->input('start');
      Cache::forget('users_start_statistic');
      Cache::forever('users_start_statistic', $users_start_statistic);
    } else if (Cache::has('users_start_statistic') && $this->request->input('start') != '') {
      $users_start_statistic = Cache::get('users_start_statistic');
    } else {
      $users_start_statistic = date('d.m.Y', strtotime(Carbon::now()->addDay(-31)));
      Cache::forget('users_start_statistic');
      Cache::forever('users_start_statistic', $users_start_statistic);
    }

    // end
    if ($this->request->input('end') && $this->request->input('end') != '') {
      $users_end_statistic = $this->request->input('end');
      Cache::forget('users_end_statistic');
      Cache::forever('users_end_statistic', $users_end_statistic);
    } else if (Cache::has('users_end_statistic') && $this->request->input('end') != '') {
      $users_end_statistic = Cache::get('users_end_statistic');
    } else {
      $users_end_statistic = date('d.m.Y');
      Cache::forget('users_end_statistic');
      Cache::forever('users_end_statistic', $users_end_statistic);
    }

    $user_id = $this->request->user()->id;
    $user_status = $this->request->user()->status;
    $start = $users_start_statistic;
    $end   = $users_end_statistic;

    if ($user_status == 5) {
      $list = Tacks::getDashOperator($user_id, $start, $end);
    } else if ($user_status == 10) {
      $list = Tacks::getDashDeveloper($user_id, $start, $end);
    } else {
      $list = Tacks::getDashAdmin($start, $end);
    }

    $proir['str'] = 0;
    $proir['med'] = 0;
    $proir['low'] = 0;
    $proir['str_p'] = 0;
    $proir['med_p'] = 0;
    $proir['low_p'] = 0;
    $proir['sigma'] = 0;

    $compl['not_work'] = 0;
    $compl['devel'] = 0;
    $compl['return'] = 0;
    $compl['in_work'] = 0;
    $compl['complete'] = 0;
    $compl['confirmed'] = 0;

    $compl['not_work_p'] = 0;
    $compl['return_p'] = 0;
    $compl['devel_p'] = 0;
    $compl['in_work_p'] = 0;
    $compl['complete_p'] = 0;
    $compl['confirmed_p'] = 0;

    if (count($list) > 0) {
      foreach ($list AS $unit) {
        if ($unit->task_priority == 1) {
          $proir['str']++;
        } else if ($unit->task_priority == 2) {
          $proir['med']++;
        } else if ($unit->task_priority == 3) {
          $proir['low']++;
        }

        if ($unit->complete == 1) {
          $compl['devel']++;
        } else if ($unit->complete == 2) {
          $compl['in_work']++;
        } else if ($unit->complete == 3 || $unit->complete == 6 || $unit->complete == 7 || $unit->complete == 8) {
          $compl['complete']++;
        } else if ($unit->complete == 4) {
          $compl['not_work']++;
        } else if ($unit->complete == 5) {
          $compl['confirmed']++;
        } else if ($unit->complete == 6) {
          $compl['return']++;
        }

        $proir['sigma']++;
      }
      $proir['str_p'] = ($proir['str'] / $proir['sigma']) * 100;
      $proir['med_p'] = ($proir['med'] / $proir['sigma']) * 100;
      $proir['low_p'] = ($proir['low'] / $proir['sigma']) * 100;

      $compl['devel_p']     = ($compl['devel']  / $proir['sigma'])    * 100;
      $compl['in_work_p']   = ($compl['in_work'] / $proir['sigma'])   * 100;
      $compl['complete_p']  = ($compl['complete'] / $proir['sigma'])  * 100;
      $compl['confirmed_p'] = ($compl['confirmed'] / $proir['sigma']) * 100;
      $compl['not_work_p']  = ($compl['not_work'] / $proir['sigma'])  * 100;
      $compl['return_p']    = ($compl['return'] / $proir['sigma'])    * 100;
    }

    return view('home', compact([
      'start',
      'end',
      'proir',
      'compl',
    ]));
  }
}
