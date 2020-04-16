<?php

namespace App\Models\Adm;

use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Image;
use Storage;

set_time_limit(250000);

/**
 * App\Models\Adm\Users
 *
 * @mixin \Eloquent
 */
class Tacks extends Model
{
  static function getGov()
  {
    return DB::table('gov_names')
      ->orderBy('gov_name', 'asc')
      ->paginate(50);
  }

  static function getGovVue($data)
  {
    return DB::table('gov_names')
      ->orderBy('gov_name', 'asc')
      ->where('gov_name', 'like', '%' . $data['gov_name'] . '%')
      ->limit(50)
      ->get();
  }

  static function getPhoneNumbsVue($data) {
      $dataStart = date("Y-m-d 00:00:00", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
      $dataEnd = date("Y-m-d H:i:s");

      return DB::connection('mysql_asteriskcdr')
          ->table('cdr')
          ->select('src')
          ->groupBy('src')
          ->orderBy('calldate', 'desc')
          ->whereIn('lastapp', ['Dial', 'Queue'])
          ->whereBetween('calldate', [$dataStart, $dataEnd])
          ->limit(50)
          ->get();
  }

  static function getNumbLastCall($idOperator) {
      $cel = DB::connection('mysql_asteriskcdr')
          ->table('cel')
          ->orderBy('id', 'desc')
          ->where('eventtype', '=', 'ANSWER')
          ->where('exten', '=', $idOperator)
          ->where('eventtime', '>', date('Y-m-d'))
          ->whereIn('appname', ['Dial', 'Queue'])
          ->limit(1)
          ->get();

      if ($cel != null && count($cel) > 0) {
          return $cel[0]->cid_num;
      } else {
          return '';
      }
  }

  static function getMail($data)
  {
    return DB::table('users_client')
      ->orderBy('client_mail', 'asc')
      ->where('client_mail', 'like', '%' . $data['client_mail'] . '%')
      ->limit(500)
      ->get();
  }

  static function getLogin($data)
  {
    return DB::table('users_client')
      ->orderBy('client_login', 'asc')
      ->where('client_login', 'like', '%' . $data['client_login'] . '%')
      ->limit(500)
      ->get();
  }

  static function getLoginUnit($data)
  {
    return DB::table('users_client')
      ->where('client_login', $data['client_login'])
      ->limit(1)
      ->get();
  }

  static function getLoginMailUnit($data)
  {
    return DB::table('users_client')
      ->where('client_mail', $data['client_mail'])
      ->limit(1)
      ->get();
  }

  static function getUserForId($data)
{
    return DB::table('users_client')
        ->select('id')
        ->where('id', $data['id'])
        ->where('client_login', $data['client_login'])
        //->where('client_mail', $data['client_mail'])
        ->limit(1)
        ->get();
}

    static function getClientForPhone($number) {
        return DB::table('users_client')
            ->where('client_phone', $number)
            ->limit(1)
            ->get();
    }

  static function getUserForLogin($data)
  {
    return DB::table('users_client')
      ->select('id')
      ->where('client_login', $data['client_login'])
      //->where('client_mail', $data['client_mail'])
      ->limit(1)
      ->get();
  }

  static function getForm()
  {
    return DB::table('form_add')
      ->orderBy('id', 'desc')
      ->get();
  }

  static function namesForm()
  {
    return DB::table('process_names')
      ->orderBy('process', 'asc')
      ->get();
  }

  static function getGovGroup()
  {
    return DB::table('gov_group')
      ->orderBy('gov_group_id', 'asc')
      ->get();
  }

  static function getProcessName()
  {
    return DB::table('process_names')
      ->orderBy('id', 'asc')
      ->paginate(100);
  }

  static function getPriors()
  {
    return DB::table('prior_days')
      ->orderBy('id', 'asc')
      ->get();
  }

  static function addGov($data)
  {
    return DB::table('gov_names')
      ->insertGetId($data);
  }

  static function addGovRole($data)
  {
    return DB::table('gov_group')
      ->insertGetId($data);
  }

  static function taskEditAdm($data) {
    $date_off = explode('.', $data['task_off']);

    if (!isset($data['developer_id'])) {
        $developer_id = null;
    } else {
        $developer_id = $data['developer_id'];
    }

    if (isset($data['task_priority'])) {
        return DB::table('tasks')
            ->where('id', $data['task_id'])
            ->update([
                'task_priority' => $data['task_priority'],
                'task_off' => $date_off[2] . '-' . $date_off[1] . '-' . $date_off[0],
                'developer_id' => $developer_id,
                'complete' => $data['complete_id'],
                'is_close' => $data['is_close'],
            ]);
    } else {
        return DB::table('tasks')
            ->where('id', $data['task_id'])
            ->update([
                'task_off' => $date_off[2] . '-' . $date_off[1] . '-' . $date_off[0],
                'developer_id' => $developer_id,
                'complete' => $data['complete_id'],
                'is_close' => $data['is_close'],
            ]);
    }
  }

  static function taskEditAdmChDate($data) {
    $date_off = explode('.', $data['task_off']);

    if (isset($data['developer_id'])) {
        return DB::table('tasks')
            ->where('id', $data['task_id'])
            ->update([
                'task_priority' => $data['task_priority'],
                'task_off' => $date_off[2] . '-' . $date_off[1] . '-' . $date_off[0],
                'developer_id' => $data['developer_id'],
            ]);
    } else {
        return DB::table('tasks')
            ->where('id', $data['task_id'])
            ->update([
                'task_priority' => $data['task_priority'],
                'task_off' => $date_off[2] . '-' . $date_off[1] . '-' . $date_off[0],
            ]);
    }
  }

  static function taskClose($data) {
      return DB::table('tasks')
          ->where('id', $data['task_id'])
          ->update([
              'is_close' => 1,
              'date_closed' => date("Y-m-d H:i:s"),
          ]);
  }

  static function taskEditDeveloper($data) {
    $date_off = explode('.', $data['task_off']);

    return DB::table('tasks')
      ->where('id', $data['task_id'])
      ->update([
        'task_priority' => $data['task_priority'],
        'task_off' => $date_off[2] . '-' . $date_off[1] . '-' . $date_off[0],
        'complete' => $data['complete_id'],
      ]);
  }

  static function taskEditDeveloperChDate($data)
  {
    $date_off = explode('.', $data['task_off']);

    return DB::table('tasks')
      ->where('id', $data['task_id'])
      ->update([
        'task_priority' => $data['task_priority'],
        'task_off' => $date_off[2] . '-' . $date_off[1] . '-' . $date_off[0],
      ]);
  }

  static function addForm($data)
  {
    return DB::table('form_add')
      ->insertGetId($data);
  }

  static function delGov($id)
  {
    return DB::table('gov_names')
      ->where('id', $id)
      ->delete();
  }

  static function delGovRole($id)
  {
    return DB::table('gov_group')
      ->where('gov_group_id', $id)
      ->delete();
  }

  static function renderTaskFiles($id)
  {
    return DB::table('tasks_files')
      ->where('task_id', $id)
      ->get();
  }

  static function getDevelopers()
  {
    return DB::table('users')
      ->where('status', 10)
      ->get();
  }

  static function getAddFilds()
  {
    return DB::table('form_add')
      ->orderByDesc('id')
      ->get();
  }

  static function delForm($id)
  {
    return DB::table('form_add')
      ->where('id', $id)
      ->delete();
  }

  static function addUserClient($data)
  {
    return DB::table('users_client')
      ->insertGetId([
        'users_id' => $data['users_id'],
        'client_login' => $data['client_login'],
        'client_pass' => $data['client_pass'],
        'gov_name' => $data['gov_name'],
        'client_fio' => $data['client_fio'],
        'client_mail' => $data['client_mail'],
        'client_phone' => $data['client_phone'],
        'client_spot' => $data['client_spot'],
        'created_at' => Carbon::now(),
      ]);
  }

  /**
   * @param $data
   * @param $userClientId
   */
  static function checkChangeAndUpdate($data, $userClientId)
  {
    $userClient = DB::table('users_client')
      ->where('id', [$userClientId])
      ->get();
    if($data['gov_name'] != $userClient[0]->gov_name){
      DB::table('users_client')
        ->where('id', $userClientId)
        ->update([
          'gov_name' => $data['gov_name'],
        ]);
    }
    if($data['client_phone'] != $userClient[0]->client_phone){
      DB::table('users_client')
        ->where('id', $userClientId)
        ->update([
          'client_phone' => $data['client_phone'],
        ]);
    }
    if($data['client_fio'] != $userClient[0]->client_fio){
      DB::table('users_client')
        ->where('id', $userClientId)
        ->update([
          'client_fio' => $data['client_fio'],
        ]);
    }
    if($data['client_spot'] != $userClient[0]->client_spot){
      DB::table('users_client')
        ->where('id', $userClientId)
        ->update([
          'client_spot' => $data['client_spot'],
        ]);
    }
    if($data['client_mail'] != null && $data['client_mail'] != $userClient[0]->client_mail){
      DB::table('users_client')
        ->where('id', $userClientId)
        ->update([
          'client_mail' => $data['client_mail'],
        ]);
    }
    if($data['client_pass'] != null && $data['client_pass'] != $userClient[0]->client_pass){
      DB::table('users_client')
        ->where('id', $userClientId)
        ->update([
          'client_pass' => $data['client_pass'],
        ]);
    }

  }

  static function addTaskClient($data)
  {
    return DB::table('tasks')
      ->insertGetId($data);
  }


  static function deleteTask($id)
  {
    DB::table('tasks')
      ->where('id', $id)
      ->delete();

    DB::table('tasks_files')
      ->where('task_id', $id)
      ->delete();

    DB::table('tasks_info')
      ->where('task_id', $id)
      ->delete();
  }

  static function addTasksFiles($data)
  {
    DB::table('tasks_files')->insert($data);
  }

  static function getSubTasks() {
    return DB::table('form_add')->get();
  }

  static function mailToCallBack($client_id)
  {
    return DB::table('users_client')
      ->select('client_mail')
      ->where('id', $client_id)
      ->get();
  }

  static function getDeveloper($developer_id)
  {
    return DB::table('users')
      ->select('email', 'name')
      ->where('id', $developer_id)
      ->get();
  }

  static function getAdviceList($data)
  {
    $query = DB::table('tasks')
      ->leftJoin('users_client', 'users_client.id', '=', 'tasks.client_id')
      ->join('users as op', 'op.id', '=', 'tasks.users_id')
      ->select(
        'tasks.id   AS id',
        'tasks.users_id      AS users_id',
        'tasks.client_id     AS client_id',
        'tasks.developer_id  AS developer_id',
        'tasks.task_type     AS task_type',
        'tasks.task_district AS task_district',
        'tasks.task_priority AS task_priority',
        'tasks.task_name     AS task_name',
        'tasks.task_off      AS task_off',
        'tasks.created_at    AS created_at',

        'users_client.id           AS client_id',
        'users_client.client_login AS client_login',
        'users_client.client_pass  AS client_pass',
        'users_client.gov_name     AS gov_name',
        'users_client.client_fio   AS client_fio',
        'users_client.client_mail  AS client_mail',
        'users_client.client_phone AS client_phone',
        'users_client.client_spot  AS client_spot',

        'op.name  AS operator'

      )->where('task_type', 'advice_tack');


    if ($data['start_go'] != '' && $data['end_go'] != '') {
      $query = $query->whereBetween('tasks.created_at', [$data['start_go'], $data['end_go']]);
    }
    if ($data['off_start_go'] != '' && $data['off_end_go'] != '') {
      $query = $query->whereBetween('tasks.task_off', [$data['off_start_go'], $data['off_end_go']]);
    }
    if ($data['priority'] != '') {
      $query = $query->where('tasks.task_priority', $data['priority']);
    }
    if ($data['task_district'] != '') {
      $query = $query->where('tasks.task_district', $data['task_district']);
    }
    if ($data['process_name'] != '') {
      $query = $query->where('tasks.task_name', 'like', '%' . $data['process_name'] . '%');
    }
    if ($data['operator'] != '') {
      $query = $query->where('op.name', 'like', '%' . $data['operator'] . '%');
    }
    if ($data['gov_name'] != '') {
      $query = $query->where('users_client.gov_name', 'like', '%' . $data['gov_name'] . '%');
    }
    if ($data['client_spot'] != '') {
      $query = $query->where('users_client.client_spot', 'like', '%' . $data['client_spot'] . '%');
    }
    if ($data['client_fio'] != '') {
      $query = $query->where('users_client.client_fio', 'like', '%' . $data['client_fio'] . '%');
    }
    if ($data['client_login'] != '') {
      $query = $query->where('users_client.client_login', 'like', '%' . $data['client_login'] . '%');
    }
    if ($data['client_phone'] != '') {
      $query = $query->where('users_client.client_phone', 'like', '%' . $data['client_phone'] . '%');
    }
    if ($data['client_mail'] != '') {
      $query = $query->where('users_client.client_mail', 'like', '%' . $data['client_mail'] . '%');
    }
    if ($data['task_id'] != '') {
      $query = $query->where('tasks.id', '=', $data['task_id']);
    }

    $query = $query->orderBy('id', 'desc');

    if($data['load_exel']==0){
      return $query->paginate(300);
    }
    else {
      return $query->limit(5000)->get();
    }

  }

  static function getTask($id)
  {
    return DB::table('tasks')
      ->leftJoin('users_client', 'users_client.id', '=', 'tasks.client_id')
      ->leftJoin('users', 'users.id', '=', 'tasks.users_id')
      ->select(
        'tasks.id   AS id',
        'tasks.users_id      AS users_id',
        'tasks.client_id     AS client_id',
        'tasks.developer_id  AS developer_id',
        'tasks.task_type     AS task_type',
        'tasks.task_district AS task_district',
        'tasks.task_priority AS task_priority',
        'tasks.task_name     AS task_name',
        'tasks.task_off      AS task_off',
        'tasks.task_term     AS task_term',
        'tasks.task_add      AS task_add',
        'tasks.created_at    AS created_at',
        'tasks.complete      AS complete',
        'tasks.is_close      AS is_close',

        'users_client.id           AS client_id',
        'users_client.client_login AS client_login',
        'users_client.client_pass  AS client_pass',
        'users_client.gov_name     AS gov_name',
        'users_client.client_fio   AS client_fio',
        'users_client.client_mail  AS client_mail',
        'users_client.client_phone AS client_phone',
        'users_client.client_spot  AS client_spot',

        'users.name  AS operator'

      )->where('tasks.id', $id)
      ->get();
  }





  static function dealTaskList($data)
  {
    $query = DB::table('tasks')
      ->leftJoin('users_client', 'users_client.id', '=', 'tasks.client_id')
      ->leftJoin('users as op', 'op.id', '=', 'tasks.users_id')
      ->leftJoin('users as de', 'de.id', '=', 'tasks.developer_id')
      ->select(
        'tasks.id   AS id',
        'tasks.users_id      AS users_id',
        'tasks.client_id     AS client_id',
        'tasks.developer_id  AS developer_id',
        'tasks.task_type     AS task_type',
        'tasks.task_district AS task_district',
        'tasks.task_priority AS task_priority',
        'tasks.task_name     AS task_name',
        'tasks.task_off      AS task_off',
        'tasks.task_term     AS task_term',
        'tasks.task_add      AS task_add',
        'tasks.created_at    AS created_at',
        'tasks.complete      AS complete',

        'users_client.id           AS client_id',
        'users_client.client_login AS client_login',
        'users_client.client_pass AS client_pass',
        'users_client.gov_name     AS gov_name',
        'users_client.client_fio   AS client_fio',
        'users_client.client_mail  AS client_mail',
        'users_client.client_phone AS client_phone',
        'users_client.client_spot  AS client_spot',

        'op.name  AS operator',
        'de.name  AS developer'

      )->where('task_type', '!=','error_tack');

    if ($data['start_go'] != '' && $data['end_go'] != '') {
      $query = $query->whereBetween('tasks.created_at', [$data['start_go'], $data['end_go']]);
    }
    if ($data['off_start_go'] != '' && $data['off_end_go'] != '') {
      $query = $query->whereBetween('tasks.task_off', [$data['off_start_go'], $data['off_end_go']]);
    }
    if ($data['task_district'] != '') {
      $query = $query->where('tasks.task_district', $data['task_district']);
    }
    if ($data['priority'] != '') {
      $query = $query->where('tasks.task_priority', $data['priority']);
    }
    if ($data['complete'] != '') {
      $query = $query->where('tasks.complete', $data['complete']);
    }
    if ($data['process_name'] != '') {
      $query = $query->where('tasks.task_name', 'like', '%' . $data['process_name'] . '%');
    }
    if ($data['operator'] != '') {
      $query = $query->where('op.name', 'like', '%' . $data['operator'] . '%');
    }
    if ($data['developer'] != '') {
      $query = $query->where('de.name', 'like', '%' . $data['developer'] . '%');
    }
    if ($data['gov_name'] != '') {
      $query = $query->where('users_client.gov_name', 'like', '%' . $data['gov_name'] . '%');
    }
    if ($data['client_spot'] != '') {
      $query = $query->where('users_client.client_spot', 'like', '%' . $data['client_spot'] . '%');
    }
    if ($data['client_fio'] != '') {
      $query = $query->where('users_client.client_fio', 'like', '%' . $data['client_fio'] . '%');
    }
    if ($data['client_login'] != '') {
      $query = $query->where('users_client.client_login', 'like', '%' . $data['client_login'] . '%');
    }
    if ($data['client_phone'] != '') {
      $query = $query->where('users_client.client_phone', 'like', '%' . $data['client_phone'] . '%');
    }
    if ($data['client_mail'] != '') {
      $query = $query->where('users_client.client_mail', 'like', '%' . $data['client_mail'] . '%');
    }
    if ($data['task_id'] != '') {
      $query = $query->where('tasks.id', '=', $data['task_id']);
    }

    $query = $query->orderBy('id', 'desc');

    if( !isset($data['load_exel']) || $data['load_exel']==0 ?? 0){
      return $query->paginate(300);
    }
    else {
      return $query->limit(5000)->get();
    }

  }













  static function getTaskList($data) {
    $query = DB::table('tasks')
      ->leftJoin('users_client', 'users_client.id', '=', 'tasks.client_id')
      ->leftJoin('users as op', 'op.id', '=', 'tasks.users_id')
      ->leftJoin('users as de', 'de.id', '=', 'tasks.developer_id')
      ->select(
        'tasks.id   AS id',
        'tasks.users_id      AS users_id',
        'tasks.client_id     AS client_id',
        'tasks.developer_id  AS developer_id',
        'tasks.task_type     AS task_type',
        'tasks.task_district AS task_district',
        'tasks.task_priority AS task_priority',
        'tasks.task_name     AS task_name',
        'tasks.task_off      AS task_off',
        'tasks.task_term     AS task_term',
        'tasks.task_add      AS task_add',
        'tasks.created_at    AS created_at',
        'tasks.complete      AS complete',
        'tasks.is_close      AS is_close',
        'tasks.date_closed      AS date_closed',
        'users_client.id           AS client_id',
        'users_client.client_login AS client_login',
        'users_client.client_pass AS client_pass',
        'users_client.gov_name     AS gov_name',
        'users_client.client_fio   AS client_fio',
        'users_client.client_mail  AS client_mail',
        'users_client.client_phone AS client_phone',
        'users_client.client_spot  AS client_spot',
        'op.name  AS operator',
        'de.name  AS developer'
      )->where('task_type', 'request_tack');

    if ($data['start_go'] != '' && $data['end_go'] != '') {
      $query = $query->whereBetween('tasks.created_at', [$data['start_go'], $data['end_go']]);
    }
    if ($data['off_start_go'] != '' && $data['off_end_go'] != '') {
      $query = $query->whereBetween('tasks.task_off', [$data['off_start_go'], $data['off_end_go']]);
    }
    if ($data['task_district'] != '') {
      $query = $query->where('tasks.task_district', $data['task_district']);
    }
    if ($data['priority'] != '') {
      $query = $query->where('tasks.task_priority', $data['priority']);
    }
    //2020-03-17 Nikolay
    //в качестве решения, для отображения
    if ($data['complete'] != '' && $data['complete'] == '11') {
        $query = $query->whereIn('tasks.complete', [3, 7 , 8, 9]);
    } else if ($data['complete'] != '') {
        $query = $query->where('tasks.complete', $data['complete']);
    }
    //если запрос произошел с главной страницы путем нажатия на диагрумму,
    // то поиск должен произойти только по открытым заявкам
    if(isset($data['is_close']) && $data['is_close'] != '') {
        $query = $query->where('tasks.is_close', '<>', '1');
    }
    if ($data['process_name'] != '') {
      $query = $query->where('tasks.task_name', 'like', '%' . $data['process_name'] . '%');
    }
    if ($data['operator'] != '') {
      $query = $query->where('op.name', 'like', '%' . $data['operator'] . '%');
    }
    if ($data['developer'] != '') {
      $query = $query->where('de.name', 'like', '%' . $data['developer'] . '%');
    }
    if ($data['gov_name'] != '') {
      $query = $query->where('users_client.gov_name', 'like', '%' . $data['gov_name'] . '%');
    }
    if ($data['client_spot'] != '') {
      $query = $query->where('users_client.client_spot', 'like', '%' . $data['client_spot'] . '%');
    }
    if ($data['client_fio'] != '') {
      $query = $query->where('users_client.client_fio', 'like', '%' . $data['client_fio'] . '%');
    }
    if ($data['client_login'] != '') {
      $query = $query->where('users_client.client_login', 'like', '%' . $data['client_login'] . '%');
    }
    if ($data['client_phone'] != '') {
      $query = $query->where('users_client.client_phone', 'like', '%' . $data['client_phone'] . '%');
    }
    if ($data['client_mail'] != '') {
      $query = $query->where('users_client.client_mail', 'like', '%' . $data['client_mail'] . '%');
    }
    if ($data['task_id'] != '') {
      $query = $query->where('tasks.id', '=', $data['task_id']);
    }

    $query = $query->orderBy('id', 'desc');

    if( !isset($data['load_exel']) || $data['load_exel']==0 ?? 0){
      return $query->paginate(300);
    }
    else {
      return $query->limit(5000)->get();
    }

  }









  static function getTaskListId($data)
  {
    $query = DB::table('tasks')
      ->leftJoin('users_client', 'users_client.id', '=', 'tasks.client_id')
      ->leftJoin('users as op', 'op.id', '=', 'tasks.users_id')
      ->leftJoin('users as de', 'de.id', '=', 'tasks.developer_id')
      ->select(
        'tasks.id   AS id',
        'tasks.users_id      AS users_id',
        'tasks.client_id     AS client_id',
        'tasks.developer_id  AS developer_id',
        'tasks.task_type     AS task_type',
        'tasks.task_district AS task_district',
        'tasks.task_priority AS task_priority',
        'tasks.task_name     AS task_name',
        'tasks.task_off      AS task_off',
        'tasks.task_term     AS task_term',
        'tasks.task_add      AS task_add',
        'tasks.created_at    AS created_at',
        'tasks.complete      AS complete',

        'users_client.id           AS client_id',
        'users_client.client_login AS client_login',
        'users_client.client_pass  AS client_pass',
        'users_client.gov_name     AS gov_name',
        'users_client.client_fio   AS client_fio',
        'users_client.client_mail  AS client_mail',
        'users_client.client_phone AS client_phone',
        'users_client.client_spot  AS client_spot',

        'op.name  AS operator',
        'de.name  AS developer'

      )->where('task_type', 'request_tack')
      ->where('developer_id', $data['developer_id']);

    if ($data['start_go'] != '' && $data['end_go'] != '') {
      $query = $query->whereBetween('tasks.created_at', [$data['start_go'], $data['end_go']]);
    }
    if ($data['off_start_go'] != '' && $data['off_end_go'] != '') {
      $query = $query->whereBetween('tasks.task_off', [$data['off_start_go'], $data['off_end_go']]);
    }
    if ($data['task_district'] != '') {
      $query = $query->where('tasks.task_district', $data['task_district']);
    }
    if ($data['priority'] != '') {
      $query = $query->where('tasks.task_priority', $data['priority']);
    }
    if ($data['complete'] != '') {
      $query = $query->where('tasks.complete', $data['complete']);
    }
    if ($data['process_name'] != '') {
      $query = $query->where('tasks.task_name', 'like', '%' . $data['process_name'] . '%');
    }
    if ($data['operator'] != '') {
      $query = $query->where('op.name', 'like', '%' . $data['operator'] . '%');
    }
    if ($data['developer'] != '') {
      $query = $query->where('de.name', 'like', '%' . $data['developer'] . '%');
    }
    if ($data['gov_name'] != '') {
      $query = $query->where('users_client.gov_name', 'like', '%' . $data['gov_name'] . '%');
    }
    if ($data['client_spot'] != '') {
      $query = $query->where('users_client.client_spot', 'like', '%' . $data['client_spot'] . '%');
    }
    if ($data['client_fio'] != '') {
      $query = $query->where('users_client.client_fio', 'like', '%' . $data['client_fio'] . '%');
    }
    if ($data['client_login'] != '') {
      $query = $query->where('users_client.client_login', 'like', '%' . $data['client_login'] . '%');
    }
    if ($data['client_phone'] != '') {
      $query = $query->where('users_client.client_phone', 'like', '%' . $data['client_phone'] . '%');
    }
    if ($data['client_mail'] != '') {
      $query = $query->where('users_client.client_mail', 'like', '%' . $data['client_mail'] . '%');
    }
    if ($data['task_id'] != '') {
      $query = $query->where('tasks.id', '=', $data['task_id']);
    }
    return $query->orderBy('id', 'desc')->paginate(300);
  }

  static function getDashAdmin($start, $end)
  {
    $start_arr = explode('.', $start);
    $end_arr = explode('.', $end);

    if (count($end_arr) < 3) {
      $end = date('Y-m-d') . ' 23:59:59';
    } else {
      $end = $end_arr[2] . '-' . $end_arr[1] . '-' . $end_arr[0] . ' 23:59:59';
    }

    if (count($start_arr) < 3) {
      $start = date('Y-m-d') . ' 00:00:00';
    } else {
      $start = $start_arr[2] . '-' . $start_arr[1] . '-' . $start_arr[0] . ' 00:00:00';
    }

    return DB::table('tasks')
        ->whereBetween('created_at', [$start, $end])
        ->where('task_type', 'request_tack')
        ->where('is_close','<>', '1')
        ->get();
  }

  static function getDashOperator($user_id, $start, $end)
  {
    $start_arr = explode('.', $start);
    $end_arr = explode('.', $end);

    if (count($end_arr) < 3) {
      $end = date('Y-m-d') . ' 23:59:59';
    } else {
      $end = $end_arr[2] . '-' . $end_arr[1] . '-' . $end_arr[0] . ' 23:59:59';
    }

    if (count($start_arr) < 3) {
      $start = date('Y-m-d') . ' 00:00:00';
    } else {
      $start = $start_arr[2] . '-' . $start_arr[1] . '-' . $start_arr[0] . ' 00:00:00';
    }

    return DB::table('tasks')
        ->whereBetween('created_at', [$start, $end])
        ->where('users_id', $user_id)
        ->where('task_type', 'request_tack')
        ->where('is_close','<>', '1')
        ->get();
  }

  static function getDashDeveloper($user_id, $start, $end)
  {
    $start_arr = explode('.', $start);
    $end_arr = explode('.', $end);

    $start = $start_arr[2] . '-' . $start_arr[1] . '-' . $start_arr[0] . ' 00:00:00';
    $end = $end_arr[2] . '-' . $end_arr[1] . '-' . $end_arr[0] . ' 23:59:59';

    return DB::table('tasks')
        ->whereBetween('created_at', [$start, $end])
        ->where('developer_id', $user_id)
        ->where('task_type', 'request_tack')
        ->where('is_close','<>', '1')
        ->get();
  }

  static function getTaskUnit($id)
  {
    return DB::table('tasks')
      ->where('id', $id)
      ->get();
  }

  static function getMess($id)
  {
    return DB::table('tasks_info')
      ->leftJoin('users', 'users.id', '=', 'tasks_info.user_id')
      ->where('task_id', $id)
      ->orderByDesc('tasks_info.id')
      ->get();
  }

  static function priorDaysCh($data)
  {
    return DB::table('prior_days')
      ->where('id', $data['id'])
      ->update(['prior' => $data['prior']]);
  }

  static function hasOperator($data)
  {
    return DB::table('tasks')
      ->where('id', $data['id'])
      ->where('users_id', $data['users_id'])
      ->count();
  }

  static function addMess($data)
  {
    return DB::table('tasks_info')
      ->insertGetId($data);
  }


  static function addNames($data)
  {
    return DB::table('process_names')
      ->insertGetId($data);
  }

  static function delNames($data)
  {
    return DB::table('process_names')
      ->delete($data);
  }


  static function getOperators()
  {
    return DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->where('status', 5)
      ->get();
  }

  static function getOperatorFromId($id)
  {
    $operator = DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->where('users.id', $id)
      ->get();

    return $operator[0]->users_phone;
  }

  static function getCalls($data)
  {
    $start_arr = explode('.', $data['start']);
    $end_arr = explode('.', $data['end']);
    //2019-12-13 Nikolay
    if (isset($data['typeCall'])) {
      $typeCall_arr = explode('.', $data['typeCall']);
    } else {
        $typeCall_arr = [];
    }
    if (isset($data['operator'])) {
        $currOperator = $data['operator'];
    } else {
        $currOperator = "";
    }

    // dd($end_arr);
    if (count($end_arr) < 3) {
      $data['end'] = date('Y-m-d') . ' 23:59:59';
    } else {
      $data['end'] = $end_arr[2] . '-' . $end_arr[1] . '-' . $end_arr[0] . ' 23:59:59';
    }

    if (count($start_arr) < 3) {
      $data['start'] = date('Y-m-d') . ' 00:00:00';
    } else {
      $data['start'] = $start_arr[2] . '-' . $start_arr[1] . '-' . $start_arr[0] . ' 00:00:00';
    }

    $query = DB::connection('mysql_asteriskcdr')
      ->table('cdr')
      ->select(array('cdr.*',
        DB::raw('SUM(case dst when 2000 then 1 else 0 end) as dst_sum'),
        DB::raw('count(linkedid) as count_linkedid'),
        DB::raw('GROUP_CONCAT(DISTINCT(dst), "|", billsec, "|", recordingfile SEPARATOR ",") as dst_billsec_rec'),
        DB::raw('SUM(case dst when 2000 then 0 else billsec end) as sum_billsec')
      ))
      ->whereIn('lastapp', ['Dial', 'Queue'])
      ;

    if ($data['operator'] != '') {
      /*
       * если задали опертаора сделаем другой селект, чтобы отобрать данные пользователя, но и также найти запись разговора,
       * в астерикс она записывается в первой записи, поэтому нам нужно указать dst = 2000,
       * и при этом именно dst_sum_operator > 0 - это будут звонки именно тек оператора
       * */
      $query->select(array('cdr.*',
        DB::raw('SUM(case dst when 2000 then 1 else 0 end) as dst_sum'),
        DB::raw('SUM(if(dst = ' . $currOperator . ' || src = ' . $currOperator . ', 1, 0)) as dst_sum_operator'),
        DB::raw('count(linkedid) as count_linkedid'),
        DB::raw('GROUP_CONCAT(DISTINCT(dst), "|", billsec, "|", recordingfile SEPARATOR ",") as dst_billsec_rec'),
        DB::raw('SUM(case dst when 2000 then 0 else billsec end) as sum_billsec')
      ));
      $query->where(
        function ($queryOperator) use ($data) {
          $queryOperator->where('dst', $data['operator'])->orWhere('src', $data['operator'])->orWhere('dst', 2000);
        }
      );
    }else{
      $query->where(function ($queryOr) {
        $queryOr->where('dst', 2000)
          ->orWhereBetween('dst', [4000, 5000])
          //$queryOr->whereBetween('dst', [4000, 5000])
          ->orWhereBetween('src', [4000, 5000]);
      });
    }

    if ($data['start'] != '' && $data['end'] != '') {
      $query->whereBetween('calldate', [$data['start'], $data['end']]);
    }

    if ($data['caller'] != '') {
      $query->where('src', 'like', '%' . $data['caller'] . '%');
    }

    //2019-12-13 Nikolay
    if (count($typeCall_arr) > 0) {
        //getting value call type
        $typeCall = $typeCall_arr[0];
        if ($typeCall == 2) {
            //income call
            $query = $query->whereRaw('length(dst) = 4');
        } elseif ($typeCall == 3) {
            //outcome call
            $query = $query->whereRaw('length(src) = 4');
        } elseif ($typeCall == 4) {
            //no answer
            $query = $query->where('disposition', '=', 'NO ANSWER');
        }
    }
    $query->groupBy('linkedid');
    if ($data['operator'] != '') {
      /*
       * если задали опертаора сделаем другой селект, чтобы отобрать данные пользователя, но и также найти запись разговора,
       * в астерикс она записывается в первой записи, поэтому нам нужно указать dst = 2000,
       * и при этом именно dst_sum_operator > 0 - это будут звонки именно тек оператора
       * */
      $query->havingRaw('(count_linkedid != 1 OR dst_sum != 1) AND dst_sum_operator > 0');
    }else{
      $query->havingRaw('count_linkedid != 1 OR dst_sum != 1');
    }

    $query->orderBy('calldate', 'desc');

    if(isset($data['limit'])){
      $query->limit($data['limit'])
        ->offset($data['offset']);
    }else{
      $query->limit(10000);
    }


    return $query->get();

  }


  static function getPlayback($uniqueid)
  {
    $playback = DB::connection('mysql_asteriskcdr')->table('cdr')
      ->select('dst')
      ->where('lastapp', 'Playback')
      ->where('uniqueid', $uniqueid)
      ->get();

    if (count($playback) > 0 && preg_match('/^[1-5]$/i', $playback[0]->dst)) {
      return $playback[0]->dst;
    } else {
      return '';
    }

  }


}





































