<?php

namespace App\Models;

use const false;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Hash;
use DB;

/**
 * App\Models\Users
 *
 * @mixin \Eloquent
 */
class Users extends Model {

  /**
   * Получение массива разрешённых функций
   * @param object $user
   * @return mixed
   */
  static function getLevels($user) {
    $levels = DB::table('access')
      ->leftJoin('level', 'level.level_id', '=', 'access.level_id')
      ->select('level.name')
      ->where('group_id', '=', $user->status)
      ->pluck('level.name')
      ->toArray();
    return $levels;
  }

  /**
   * Проверка доступа из middleware
   * @param object $user
   * @param string $method имя проверяемого метода, получаемого из URL
   * @return bool
   */
  static function checkAccessMiddleware($user, $method) {
    $user_level = DB::table('level')
      ->leftJoin('access', 'level.level_id', '=', 'access.level_id')
      ->select('access.group_id AS group_id')
      ->where('level.name', '=', $method)
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
  static function add($info) {
    $id = DB::table('users')->insertGetId(array(
      'name' => strip_tags(trim($info['username'])),
      'email' => strip_tags(trim($info['email'])),
      'password' => Hash::make($info['password']),
      'status' => (int) $info['access'],
      'remember_token' => $info['_token'],
      'updated_at' => date('Y-m-d H:i:s', (strtotime(gmdate('Y-m-d H:i:s')) + 3 * 60 * 60)),
      'created_at' => date('Y-m-d H:i:s', (strtotime(gmdate('Y-m-d H:i:s')) + 3 * 60 * 60))
    ));
    if ($id > 0) {
      DB::table('users_info')->insertGetId(array(
        'idUser' => (int) $id,
        'gender' => (int) $info['gender'],
        'birthday' => strtotime($info['birthday']),
        'firstname' => strip_tags(trim($info['firstname'])),
        'lastname' => strip_tags(trim($info['lastname']))
      ));
    }
    return $id;
  }

  /**
   * Получение списка пользователей
   * @return array
   */
  static function getUsersList() {
    $usersList = [];
    $users = DB::table('users')
      ->leftJoin('user_group', 'users.status', '=', 'user_group.group_id')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.idUser')
      ->leftJoin('users_login_info', 'users.id', '=', 'users_login_info.user_id')
      ->where('users.status', '<=', 10)
      ->orderBy('users.status', 'users.name', 'users_login_info.date_logout desc')
      ->get();

    foreach ($users AS $_u) {
      $usersList[$_u->group_id]['group'] = $_u->group;
      $usersList[$_u->group_id]['active'] = 'inactive';
      $usersList[$_u->group_id][$_u->id]['id'] = Crypt::encryptString($_u->idUser);
      $usersList[$_u->group_id][$_u->id]['name'] = $_u->name;
      $usersList[$_u->group_id][$_u->id]['email'] = $_u->email;
      $usersList[$_u->group_id][$_u->id]['firstname'] = $_u->firstname;
      $usersList[$_u->group_id][$_u->id]['lastname'] = $_u->lastname;
      $usersList[$_u->group_id][$_u->id]['lastname'] = $_u->lastname;
      $usersList[$_u->group_id][$_u->id]['in_site'] = 'inactive';

      if ($_u->date_logout != null) {
        $usersList[$_u->group_id][$_u->id]['logout'] = 'logout: ' . date('Y/m/d H:i:s', $_u->date_logout);
      }

      if ($_u->date_logout == null && $_u->date_login == null) {
        $usersList[$_u->group_id][$_u->id]['logout'] = '';
        $usersList[$_u->group_id][$_u->id]['login'] = 'did not login';
      } else if ($_u->date_logout == null && $_u->date_login > 0) {
        $usersList[$_u->group_id][$_u->id]['login'] = 'active with: ' . date('Y/m/d H:i:s', $_u->date_login);
        $usersList[$_u->group_id]['active'] = 'active';
        $usersList[$_u->group_id][$_u->id]['in_site'] = 'active';
      } else {
        $usersList[$_u->group_id][$_u->id]['login'] = 'login: ' . date('Y/m/d H:i:s', $_u->date_login);
      }
    }

    return $usersList;
  }

  /**
   * Получение списка уровней доступа
   * @return array
   */
  static function getAccessList() {
    $data = [];
    $levels = DB::table('level')->orderBy('level_group')->get();
    $user_group = DB::table('user_group')
      ->leftJoin('access', 'user_group.group_id', '=', 'access.group_id')
      ->select('user_group.group_id AS id', 'user_group.group AS group', 'access.level_id AS level')
      ->orderBy('user_group.group_id')
      ->get();
    foreach ($levels AS $_k => $_levels) {
      $data['levels'][$_k]['level_title'] = $_levels->level_title;
      $data['levels'][$_k]['level_id_c'] = Crypt::encryptString($_levels->level_id);
      $data['levels'][$_k]['level_id'] = $_levels->level_id;
      $data['levels'][$_k]['level_comment'] = $_levels->level_comment;
    }
    foreach ($user_group AS $group) {
      $data['users'][$group->id]['id'] = Crypt::encryptString($group->id);
      $data['users'][$group->id]['group'] = $group->group;
      $data['users'][$group->id]['level'][$group->level] = $group->level;
    }
    return $data;
  }

  /**
   * Получение списка групп пользователей
   * @return array
   */
  static function getGroupList() {
    return DB::table('user_group')->orderBy('group_id')->get();
  }

  /**
   * Получение группы пользователя по ID
   * @param integer $group_id
   * @return mixed
   */
  static function getGroupById($group_id) {
    return DB::table('user_group')->where('group_id', '=', $group_id)->get();
  }

  /**
   * Получение информации о пользователе по ID
   * @param integer $idUser
   * @return mixed
   */
  static function getUserInfo($idUser) {
    $decrypId = Crypt::decryptString($idUser);
    $data['user'] = DB::table('users')->where('id', $decrypId)->get();
    $data['info'] = DB::table('users_login_info')->where('user_id', $decrypId)->orderBy('date_login', 'desc')->get();
    return $data;
  }

  /**
   * Перезапись уровня доступа для группы пользователей
   * @param integer $level уровень доступа
   * @param integer $group группа пользователей
   * @param boolean $status статус (вкл/выкл)
   */
  static function changeAccess($level, $group, $status) {
    if ($status == false) {
      DB::table('access')->where('group_id', '=', Crypt::decryptString($group))->where('level_id', '=', Crypt::decryptString($level))->delete();
    } else {
      DB::table('access')->insert(array('group_id' => Crypt::decryptString($group), 'level_id' => Crypt::decryptString($level)));
    }
  }


}
