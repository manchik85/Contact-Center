<?php

namespace App\Models\Adm;

use Cache;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Image;
use Storage;

set_time_limit(250000);

/**
 * App\Models\Adm\Users
 *
 * @mixin \Eloquent
 */
class Users extends Model
{

  /**
   * Получение кол-ва всех пользователей
   * @return mixed
   */
  static function getAll()
  {
    return DB::table('users')->where('status', '<', 99)->count();
  }

  /**
   * Получение кол-ва всех пользователей - Oператоров
   * @return mixed
   */
  static function getAllOperators()
  {
    return DB::table('users_statistic')
      ->leftJoin('users', 'users_statistic.user_id', '=', 'users.id')
      ->get();
  }

  static function getAllOperatorsForLogout()
  {
    return DB::table('users_statistic')
      ->leftJoin('users', 'users_statistic.user_id', '=', 'users.id')
      ->leftJoin('users_info', 'users_statistic.user_id', '=', 'users_info.users_id')
      ->where('users.status', 5)
      ->where('users_statistic.user_event', '!=','offline')
      ->groupBy('users.id')
      ->get();
  }

  /**
   * Получение массива разрешённых функций
   * @param object $user
   * @return mixed
   */
  static function getLevels($user)
  {
    return DB::table('access')
      ->leftJoin('user_permissions', 'user_permissions.user_permissions_id', '=', 'access.level_id')
      ->select('user_permissions.user_permissions_name')
      ->where('group_id', '=', @$user->status)
      ->pluck('user_permissions.user_permissions_name')
      ->toArray();
  }

  /**
   * Проверка доступа из middleware
   * @param object $user
   * @param string $method имя проверяемого метода, получаемого из URL
   * @return bool
   */
  static function checkAccessMiddleware($user, $method)
  {
    $user_level = DB::table('user_permissions')
      ->leftJoin('access', 'user_permissions.user_permissions_id', '=', 'access.level_id')
      ->select('access.group_id AS group_id')
      ->where('user_permissions.user_permissions_name', '=', $method)
      ->where('access.group_id', '=', @$user->status)
      ->get();
    if (@$user_level[0]->group_id != @$user->status) {
      return false;
    }
    return true;
  }

  /**
   * Запись в базу информации о пользователе
   * @param array $info полученные из формы данные
   * @return mixed
   */
  static function add($info)
  {
    $id = DB::table('users')
      ->insertGetId([
        'name' => strip_tags(trim($info['username'])),
        'email' => strip_tags(trim($info['email'])),
        'password' => Hash::make($info['password']),
        'status' => (int)$info['access'],
        'remember_token' => $info['_token'],
        'updated_at' => date('Y-m-d H:i:s', (strtotime(gmdate('Y-m-d H:i:s')) + 3 * 60 * 60)),
        'created_at' => date('Y-m-d H:i:s', (strtotime(gmdate('Y-m-d H:i:s')) + 3 * 60 * 60))
      ]);
    if ($id > 0) {
      DB::table('users_info')
        ->insertGetId([
          'idUser' => (int)$id,
          'gender' => (int)$info['gender'],
          'birthday' => strtotime($info['birthday']),
          'firstname' => strip_tags(trim($info['firstname'])),
          'lastname' => strip_tags(trim($info['lastname']))
        ]);
    }
    return $id;
  }

  /**
   * Получение списка пользователей
   * @return array
   */
  static function getUsersList()
  {
    return DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->leftJoin('user_group', 'users.status', '=', 'user_group.group_id')
      ->where('users.status', '<=', 90)
      ->orderBy('users.status', 'asc', 'users.name')
      ->get();
  }

  /**
   * Получение списка пользователей
   * @return array
   */
  static function getOperatorsList()
  {
    return DB::table('users')
      ->where('status', '=', 5)
      ->orderBy('name', 'asc')
      ->get();
  }

    /**
     * Получение списка пользователей по его статусу
     * @return array
     */
    static function getUsersListByStatus($status) {
        return DB::table('users')
            ->where('status', '=', $status)
            ->orderBy('name', 'asc')
            ->get();
    }

  /**
   * Получение списка уровней доступа
   * @return array
   */
  static function getAccessList()
  {
    return DB::table('user_permissions')
      ->join('user_permissions_group', 'user_permissions.user_permissions_group_id', '=', 'user_permissions_group.user_permissions_group_id')
      ->orderBy('user_permissions.user_permissions_group_id')
      ->get();
  }

  /**
   * Получение списка Групп уровней доступа
   *
   * @param $data
   * @return array
   */
  static function getGrouAccessList($data)
  {
    return DB::table('user_group')
      ->leftJoin('access', 'user_group.group_id', '=', 'access.group_id')
      ->select(
        'user_group.group_id AS id',
        'user_group.group AS group',
        'access.level_id AS permissions'
      )
      ->where('user_group.group_id', '<=', $data['user']->status)
      ->orderBy('user_group.group_id')
      ->get();
  }

  /**
   * Получение списка групп пользователей
   * @return array
   */
  static function getGroupList()
  {
    return DB::table('user_group')
      ->orderBy('group_id')
      ->get();
  }

  /**
   * Получение группы пользователя по ID
   * @param integer $group_id
   * @return mixed
   */
  static function getGroupById($group_id)
  {
    return DB::table('user_group')
      ->where('group_id', '=', $group_id)
      ->get();
  }

  /**
   * Получение информации о пользователе по ID
   * @param integer $idUser
   * @return mixed
   */
  static function getUsersData($idUser)
  {
    $data = DB::table('users_info')
      ->where('users_id', $idUser)
      ->get();
    return $data;
  }

  /**
   * Получение Инфы о Клиенте по ID
   *
   * @param $idUser
   * @return mixed
   */
  static function getUserAllInfo($idUser)
  {
    $data = DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->where('id', $idUser)
      ->get();
    return $data[0];
  }

  static function getUserCons($idUser, $start, $end, $request_tack)
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
      ->where('users_id', $idUser)
      ->where('task_type', $request_tack)
      ->count();
  }

  static function getAsteriskStatistic($users_phone, $start, $end)
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

    return DB::connection('mysql_asteriskcdr')->table('cdr')
      ->whereBetween('calldate', [$start, $end])
      ->where('dst', $users_phone)
      ->where('lastapp', 'Dial')
      ->where('src', '>', 9999)
      ->where('disposition', '!=', 'BUSY')
      ->get();
  }

  static function getAsteriskMarks($users_phone, $start, $end)
  {
    return DB::connection('mysql_asteriskcdr')->table('survey')
      ->whereBetween('date', [$start, $end])
      ->where('operator', $users_phone)
      ->get();
  }

  static function getAsterisDial($users_phone, $start, $end)
  {
    return DB::connection('mysql_asteriskcdr')->table('cdr')
      ->whereBetween('calldate', [$start, $end])
      ->where('dst', $users_phone)
      ->where('src', '>', 9999)
      ->where('lastapp', 'Dial')
      ->get();
  }

  static function getAsteriskCallStatistic($start, $end)
  {
    /*
    получение статистики по звонкам
    Кол-во - quantity
    Кол-во пропущенных - missed
    Среднее время ожидания на линии - waitAverageTime
    Select COUNT(*) as quantity,
    SUM( if( callTime = 0, 1, 0) ) as missed,
    AVG(waitTime) as waitAverageTime
    FROM (
      SELECT SUM(case dst when 2000 then 1 else 0 end) as dst_sum,
      count(linkedid) as count_linkedid,
      SUM(case dst when 2000 then 0 else billsec end) as callTime,
      SUM(case dst when 2000 then billsec else 0 end) as waitTime
      FROM cdr
      where cdr.lastapp in ('Dial', 'Queue')
      AND (cdr.dst = 2000 OR cdr.dst between 4000 and 5000 OR cdr.src between 4000 and 5000)
       AND cdr.calldate between '2019-11-15 00:00:00' and '2019-11-15 23:59:59'
      GROUP BY cdr.linkedid
      HAVING count_linkedid != 1 OR dst_sum != 1 -- or src_sum = 1
      ORDER BY vr desc
      ) tbl
  */
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
      return DB::connection('mysql_asteriskcdr')
        ->table(DB::raw('(SELECT *, SUM(case dst when 2000 then 1 else 0 end) as dst_sum, '
          . 'count(linkedid) as count_linkedid, '
          . 'SUM(case dst when 2000 then 0 else billsec end) as callTime, '
          . 'SUM(case dst when 2000 then billsec else 0 end) as waitTime '
          . 'FROM cdr '
          . 'where cdr.lastapp in (\'Dial\', \'Queue\') '
          . 'AND (cdr.dst = 2000 OR cdr.dst between 4000 and 5000 OR cdr.src between 4000 and 5000) '
  . 'AND cdr.calldate between \'' . $start . '\' and \'' . $end . '\' '
  . 'GROUP BY cdr.linkedid '
  . 'HAVING count_linkedid != 1 OR dst_sum != 1 '
  . ') as tbl '))
        ->select(array(
          DB::raw('COUNT(*) as quantity '),
          DB::raw('SUM( if( callTime = 0, 1, 0) ) as missed '),
          DB::raw('AVG(waitTime) as waitAverageTime '),
        ))
        ->get()
        ;
  }

  /**
   * Получение информации о Клиенте по ID
   * @param $idUser
   * @return mixed
   */
  static function getClientData($idUser)
  {
    $data = DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->leftJoin('users_info_client', 'users.id', '=', 'users_info_client.users_id')
      ->leftJoin('users_coc_client', 'users.id', '=', 'users_coc_client.users_id')
      ->where('users.id', $idUser)
      ->get();

    $geo = DB::table('geo_city')
      ->leftJoin('geo_region', 'geo_city.region_id', '=', 'geo_region.region_id')
      ->leftJoin('geo_country', 'geo_region.country_id', '=', 'geo_country.country_id')
      ->select(
        'geo_city.city_id as city_id',
        'geo_region.region_id as region_id',
        'geo_country.country_id as country_id',

        'geo_country.name as country_name',
        'geo_region.name as region_name',
        'geo_city.name as city_name'
      )
      ->where('geo_city.city_id', $data[0]->evCity)
      ->get();


    $data[0]->city_name = $geo[0]->city_name;
    $data[0]->region_name = $geo[0]->region_name;
    $data[0]->country_name = $geo[0]->country_name;

    $data[0]->city_id = $geo[0]->city_id;
    $data[0]->region_id = $geo[0]->region_id;
    $data[0]->country_id = $geo[0]->country_id;


    $data[0]->region_all = DB::table('geo_region')
      ->where('country_id', 3159)
      ->get();
    $data[0]->geo_country = DB::table('geo_country')
      ->orderBy('geo_country.name')
      ->get();
    $data[0]->geo_region = DB::table('geo_region')
      ->where('country_id', '=', $geo[0]->country_id)
      ->orderBy('geo_region.name')
      ->get();
    $data[0]->geo_city = DB::table('geo_city')
      ->where('region_id', '=', $geo[0]->region_id)
      ->orderBy('geo_city.name')
      ->get();

    return $data;
  }


  static function getClientDataADM($idUser)
  {
    return DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->where('users.id', $idUser)
      ->get();
  }

  static function getStatisticData($data)
  {
    $start_arr = explode('.', $data['start']);
    $end_arr   = explode('.', $data['end']);

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

    return DB::table('users_statistic')
      ->where('users_statistic.user_id', $data['users_id'])
      ->whereBetween('users_statistic.user_time', [$start, $end])
      ->orderBy('users_statistic.id', 'desc')
      ->paginate(150);
  }


  static function getAllStatisticData($data)
  {
    $start_arr = explode('.', $data['start']);
    $end_arr   = explode('.', $data['end']);

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

    return DB::table('users_statistic')
      ->where('users_statistic.user_id', $data['users_id'])
      ->whereBetween('users_statistic.user_time', [$start, $end])
      ->orderBy('users_statistic.id', 'desc')
      ->limit(50000)
      ->get();
  }

  static function getAllStatisticUsers($status, $gov_group)
  {
    $query =  DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->where('users.status', $status);

    if($gov_group!=''){
      $query = $query->where('users_info.gov_group', $gov_group)
        ->orWhere('users_info.gov_group_master', $gov_group)
        ->orWhere('users_info.gov_group_root', $gov_group);
    }
    return $query->get();
  }

  /**
   * Перезапись уровня доступа для группы пользователей
   * @param integer $level уровень доступа
   * @param integer $group группа пользователей
   * @param boolean $status статус (вкл/выкл)
   */
  static function changeAccess($level, $group, $status)
  {

    DB::table('access')
      ->where('group_id', '=', Crypt::decryptString($group))
      ->where('level_id', '=', Crypt::decryptString($level))
      ->delete();

    if ($status == 'true') {
      DB::table('access')
        ->insert([
          'group_id' => Crypt::decryptString($group),
          'level_id' => Crypt::decryptString($level)
        ]);
    }
  }

  /**
   * Изменение основной админской информации
   * @param array $data
   */
  static function admChengeCommonInfo($data)
  {
//            dd($data);

    DB::table('users')
      ->where('id', $data['user_id'])
      ->update([
        'name' => $data['users_name'],
        'email' => $data['users_email']
      ]);

    $ust = DB::table('users_info')
      ->where('users_id', $data['user_id'])
      ->get();

    if (count($ust) > 0) {
      DB::table('users_info')
        ->where('users_id', $data['user_id'])
        ->update([
          'users_phone' => $data['users_phone'],
          'users_cont_phone' => $data['users_cont_phone'],
          'gov_group' => $data['gov_group'],
          'gov_group_root' => $data['gov_group_root'],
          'gov_group_master' => $data['gov_group_master'],
        ]);
    } else {
      DB::table('users_info')
        ->insert([
          'users_id' => $data['user_id'],
          'users_phone' => $data['users_phone'],
          'users_cont_phone' => $data['users_cont_phone'],
          'gov_group' => $data['gov_group'],
          'gov_group_root' => $data['gov_group_root'],
          'gov_group_master' => $data['gov_group_master'],
        ]);
    }
  }

  /**
   * Изменение Аккаунта пользователя
   * @param $user_id
   * @param $data
   * @return int
   */
  static function updateAcauntData($user_id, $data)
  {
    $id = 0;
    $st = self::admUnicCommonInfo(['mail' => $data['email'], 'id' => $user_id]);
    if ($st == 0) {
      $id = DB::table('users')
        ->where('id', $user_id)
        ->update($data);
    }
    return $id;
  }

  /**
   * Проверка уникальности Логина
   *
   * @param $data
   * @return mixed
   */
  static function admUnicCommonInfo($data)
  {
    return DB::table('users')
      ->where('id', '<>', $data['id'])
      ->where('email', $data['mail'])
      ->select('id')
      ->get();
  }

  /**
   * Проверка уникальности Логина
   *
   * @param $data
   * @return mixed
   */
  static function admUnicEmail($data)
  {
    return DB::table('users')
      ->where('email', $data['mail'])
      ->select('id')
      ->get();
  }


  /**
   * Изменение Аккаунта пользователя
   *
   * @param $user_id
   * @param $data
   * @return mixed
   */
  static function updateCocData($user_id, $data)
  {
    $data['users_id'] = $user_id;
    array_pull($data, 'name');
    array_pull($data, 'email');

    DB::table('users_coc_client')
      ->where('users_id', $user_id)
      ->delete();
    return DB::table('users_coc_client')
      ->insert($data);
  }

  /**
   * Страны
   * @return mixed
   */
  static function country()
  {
    $data = DB::table('geo_country')
      ->orderBy('geo_country.name')
      ->get();
    return $data;
  }

  /**
   * Регионы
   * @param int $country_id
   * @return mixed
   */
  static function region($country_id = 3159)
  {
    $data = DB::table('geo_region')
      ->where('country_id', '=', $country_id)
      ->orderBy('geo_region.name')
      ->get();
    return $data;
  }

  /**
   * Города
   * @param int $region_id
   * @return mixed
   */
  static function city($region_id = 4312)
  {
    $data = DB::table('geo_city')
      ->where('region_id', '=', $region_id)
      ->orderBy('geo_city.name')
      ->get();
    return $data;
  }

  /**
   * Создаём Пользователя
   *
   * @param $data
   * @param $user_id
   * @return int
   */
  static function admAddlUser($data, $user_id)
  {
    $users_id = 0;
    $st = self::admUnicCommonInfo(['id' => $user_id, 'mail' => $data['email']]);
    if ($st == 0) {
      $users_id = DB::table('users')
        ->insertGetId([
          'name' => $data['name'],
          'email' => $data['email'],
          'password' => bcrypt($data['password']),
          'status' => (int)$data['status'],
          'updated_at' => date('Y-m-d H:i:s'),
          'created_at' => date('Y-m-d H:i:s'),
        ]);

      DB::table('users_info')
        ->insert([
          'users_id' => $users_id,
          'users_phone' => $data['users_phone'],
          'users_deck' => $data['users_deck'],
        ]);
    }
    return $users_id;
  }

  static function admAddUserInfo($data, $users_id)
  {


    DB::table('users_info')
      ->insert([
        'users_id' => $users_id,
        'users_phone' => $data['users_phone'],
        'users_deck' => $data['users_deck'],
        'users_cont_phone' => $data['users_cont_phone'],
        'gov_group' => $data['gov_group'],

        'gov_group_master' => $data['gov_group_master'],
        'gov_group_root' => $data['gov_group_root'],
      ]);
  }

  /**
   * @param $idUser
   * @return mixed
   */
  static function getUserName($idUser)
  {
    $data = DB::table('users')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->where('id', $idUser)
      ->get();
    return $data[0];
  }

  /**
   * Изменение Своего пароля
   * @param $data
   * @return int
   */
  static function admChengePassw($data)
  {
    $pst = 0;
    $st = self::checkUsersPass($data['oldPassword'], $data['user_id']);

    if ($st > 0 && $data['new-password'] == $data['password-confirm']) {
      $pst = DB::table('users')
        ->where('id', $data['user_id'])
        ->update([
          'password' => Hash::make($data['new-password'])
        ]);
    }

    return $pst;
  }

  static function admChangePas123()
  {
    $pst = 0;
      $pst = DB::table('users')
        ->update([
          'password' => Hash::make(123456)
        ]);

    return $pst;
  }

  /**
   * Проверка пользовательского пароля
   * @param $oldPassword
   * @param $userId
   * @return int
   */
  static function checkUsersPass($oldPassword, $userId)
  {
    $results = DB::table('users')
      ->where('id', $userId)
      ->get();

    if (isset($results[0]->id)) {
      if (Hash::check($oldPassword, $results[0]->password)) {
        $st = $results[0]->id;
      } else {
        $st = 0;
      }
    } else {
      $st = 0;
    }
    return $st;
  }

  /**
   * Изменение пользовательского пароля
   * @param $data
   * @return int
   */
  static function admChengeUserPassw($data)
  {
    return DB::table('users')
      ->where('id', $data['user_id'])
      ->update(['password' => Hash::make($data['new-password'])]);
  }

  /**
   * Изменение пользовательского Описания
   * @param $data
   * @return int
   */
  static function admChengeDeckript($data)
  {
    return DB::table('users_info')
      ->where('users_id', $data['user_id'])
      ->update(['users_deck' => $data['users_deck']]);
  }

  /**
   * Баним Пользователя
   * @param $users_id
   * @return mixed
   */
  static function admBanUsers($users_id)
  {
    $pst = DB::table('users')
      ->where('id', (int)$users_id)
      ->update(['status' => 1]);
    return $pst;
  }

  /**
   * Удаляем Пользователя
   * @param $users_id
   */
  static function admDeleteUsers($users_id)
  {
    DB::table('users')
      ->where('id', (int)$users_id)
      ->delete();
    DB::table('users_info')
      ->where('users_id', (int)$users_id)
      ->delete();

    DB::table('users_client')
      ->where('users_id', (int)$users_id)
      ->delete();

    DB::table('tasks')
      ->where('users_id', (int)$users_id)
      ->delete();

    DB::table('users_statistic')
      ->where('user_id', (int)$users_id)
      ->delete();
  }

  /**
   * Удаление логотипа
   * @param $id
   */
  static function removeLogo($id)
  {
    Storage::disk('logoFull')
      ->delete($id . '.jpg');
    Storage::disk('logoPrem')
      ->delete($id . '.jpg');
    Storage::disk('logoPubl')
      ->delete($id . '.jpg');
  }

  /**
   * Смена статуса Пользователя
   * @param $users_id
   * @param $level_user
   */
  static function admChenLevelUser($users_id, $level_user)
  {
    DB::table('users')
      ->where('id', (int)$users_id)
      ->update(['status' => (int)$level_user]);
  }

  /**
   * Резиденет Российской Федерации да/нет - переключение
   * @param $id
   * @param $st
   */
  static function switchResidentRF($id, $st)
  {
    DB::table('users_info_client')
      ->where('users_id', $id)
      ->update(['resident' => $st]);
  }

  /**
   * Редактор профиля Пользователя
   * @param $id
   * @param $insert
   * @param $file
   */
  static function profileEdit($id, $insert, $file)
  {
    if (!isset($insert['resident'])) {
      $insert['resident'] = 0;
    }
    if (!isset($insert['st_phone'])) {
      $insert['st_phone'] = '';
    }
    if (!isset($insert['cb_goods'])) {
      $insert['cb_goods'] = '';
    }
    if (!isset($insert['cb_services'])) {
      $insert['cb_services'] = '';
    }
    if (!isset($insert['cb_proizv_obekty'])) {
      $insert['cb_proizv_obekty'] = '';
    }

    DB::table('users_info_client')
      ->where('users_id', $id)
      ->update([
        'face' => $insert['face'],
        'face_name' => $insert[$insert['face'] . '-name'],
        'face_birthday' => $insert[$insert['face'] . '-birthday'],
        'resident' => $insert['resident'],
        'evCity' => $insert['evCity'],
        'fiz_bik_inn' => $insert['fiz-bik-inn'],
        'st_phone' => $insert['st_phone'],
        'cb_status' => json_encode($insert['cb_status']),
        'cb_business_region' => json_encode($insert['cb_business_region']),
        'cb_goods' => json_encode($insert['cb_goods']),
        'cb_services' => json_encode($insert['cb_services']),
        'cb_proizv_obekty' => json_encode($insert['cb_proizv_obekty']),
      ]);

    if ($file != false) {

      $img = Image::make($file);

      $full = public_path() . '/usersdata/logotypes/full/';
      $prem = public_path() . '/usersdata/logotypes/prem/';
      $publ = public_path() . '/usersdata/logotypes/publ/';
      $resol = 'jpg';

      $img->resize(800, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })
        ->save($full . $id . '.' . $resol);

      $img->resize(250, 250, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })
        ->save($prem . $id . '.' . $resol);

      $img->resize(64, 64, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      })
        ->save($publ . $id . '.' . $resol);
    }

  }

  static function isOnline($id)
  {
    $has_status = DB::table('users_statistic')
      ->where('user_id', $id)
      ->orderBy('id', 'desc')
      ->limit(1)
      ->get();

    //dd($has_status[0]->user_event);

    $status = false;

    if (@$has_status[0]->user_event == 'online') {
      $status = 1;
    } else if (@$has_status[0]->user_event == 'aut') {
      $status = 2;
    }

    return $status;
  }




  static function isOnlineInPhone($id)
  {
    $playback = DB::connection('mysql_asteriskcdr')->table('cel')
      ->select('id')
      ->where('eventtype', 'CHAN_END')
      ->where('cid_name', $id)
      ->where('eventtime', 'like', '%' . date('Y-m-d H:i') . '%')
      ->limit(1)
      ->get();

    $status = false;

    if ( isset($playback[0]) && $playback[0]->id>0 ) {
      $status = 1;
    }

    return $status;

  }



  static function isOnlineAll()
  {
    return DB::table('users_statistic')
      ->orderBy('id', 'desc')
      ->limit(10000)
      ->get();
  }



  static function getUserStrike($email)
  {
    return DB::table('users')
      ->where('email', $email)
      ->get();
  }



  static function fixStrike($data, $ip)
  {
    return DB::table('users_statistic')
      ->insertGetId([
        'user_id'=>$data->id,
        'user_event'=>'error',
        'agent' => $ip,
        'user_time'=>Carbon::now(),
      ]);
  }


  static function getStrike($data)
  {
    return DB::table('users as u')
      ->leftJoin('users_statistic as s', 'u.id', '=', 's.user_id')
      ->where('u.email', $data['email'])
      ->where('s.user_event', 'error')
      ->whereBetween('user_time', [ Carbon::now()->subMinute(), Carbon::now() ])
      ->orderByDesc('user_time')
      ->get();
  }


  /**
   * @param $id
   * @param $status
   * @param string $out_desc
   * @param $ip
   */
  static function checkLogin($id, $status, $out_desc='', $ip)
  {
    DB::table('users_statistic')
      ->insert([
        'user_id' => $id,
        'user_event' => $status,
        'agent' => $ip,
        'out_desc' => $out_desc,
        'user_time' => Carbon::now()
      ]);
  }

  static function setLogoutSockets($id, $status, $time)
  {
    DB::table('users_statistic')
      ->insert([
        'user_id' => $id,
        'user_event' => $status,
        'user_time' => $time
      ]);
  }

  static function pongOperatorModel($id, $time)
  {
    DB::table('users_statistic')
      ->where('user_id', $id)
      ->where('user_time', $time)
      ->where('user_event', 'offline')
      ->delete();
  }

  static function nonLogout($id)
  {
    return DB::table('users_statistic')
      ->where('user_id', $id)
      ->orderBy('id', 'desc')
      ->limit(1)
      ->get();
  }

  static function permissionGroup()
  {
    return DB::table('user_group')
      ->whereBetween('group_id', [2, 98])
      ->get();
  }

  /**
   * Проверка с какого IP зашёл пользователь
   * @param array $ip
   * @return bool
   */
  static function checkWhiteIp($ip)
  {
    return DB::table('ip_white_list')
      ->whereIn('white_ip', [
        $ip[0] . '.*',
        $ip[0] . '.' . $ip[1] . '.*',
        $ip[0] . '.' . $ip[1] . '.' . $ip[2] . '.*',
        $ip[0] . '.' . $ip[1] . '.' . $ip[2] . '.' . $ip[3]
      ])->count();
  }


  /**
   * Получение списка разрешённых IP
   * @return array
   */
  static function getIpList()
  {
    return DB::table('ip_white_list')->orderBy('ip_id', 'desc')->get();
  }

  /**
   * Удаление разрешённого IP
   * @param $data
   * @return int
   */
  static function removeWhiteIp($data)
  {
    return DB::table('ip_white_list')->where('ip_id', $data['ip'])->delete();
  }

  /**
   * Добавление разрешённого IP
   * @param $data
   * @return bool
   */
  static function addWhiteIp($data)
  {
    return DB::table('ip_white_list')->insertGetId(['white_ip' => $data['ip']]);
  }


  static function getGovGroup()
  {
    return DB::table('gov_group')
      ->orderBy('gov_group_id', 'asc')
      ->get();
  }


}
