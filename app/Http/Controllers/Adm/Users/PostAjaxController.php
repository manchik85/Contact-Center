<?php

namespace App\Http\Controllers\Adm\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\userAddPost;
use App\Models\Adm\Users;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use \Illuminate\Support\Facades\Artisan;

class PostAjaxController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }


  /**
   * POST-контроллер изменения статуса Операвтора на Отошёл
   *
   * @param userAddPost $request
   */
  public function operatorAut()
  {
    $id = (int)$this->request->input('users_id');
    $out_desc = $this->request->input('out_desc');

    $phone = Users::getUsersData($id);

    if( count($phone) ) {
      Artisan::call('operatorStatus:stop ' . $phone[0]->users_phone);
    }

    if (!Cache::has('aut' . $id)) {
      Users::checkLogin($id, 'aut',  $out_desc, $_SERVER['REMOTE_ADDR']);
      $minutes = Carbon::now()->addMinutes(30);
      Cache::forget('online' . $id);
      Cache::add('aut' . $id, time(), $minutes);
    }
  }



  /**
   * AJAX-контроллер изменения уровня доступа
   */
  public function usersChangeAccess()
  {
    $level = $this->request->input('level');
    $group = $this->request->input('group');
    $status = $this->request->input('status');
    Users::changeAccess($level, $group, $status);
  }

  /**
   * AJAX-контроллер забанить пользователя
   */
  public function banUser()
  {
    $users_id = $this->request->input('users_id');

    $pst = Users::admBanUsers((int)$users_id);
    print json_encode(['st' => $pst]);
  }

  /**
   * AJAX-контроллер удаление Пользователя
   */
  public function deleteUser()
  {
    $users_id = $this->request->input('users_id');
    $pst = Users::admDeleteUsers((int)$users_id);
    print json_encode(['st' => $pst]);
  }

  /**
   * AJAX-контроллер Смена статуса Пользователя
   */
  public function chenLevelUser()
  {
    $users_id = $this->request->input('users_id');
    $level_user = $this->request->input('level_user');
    if ($this->request->user()->status >= $level_user) {
      Users::admChenLevelUser((int)$users_id, (int)$level_user);
    }
    return redirect()->route('admin.list_users');
  }


  /**
   * AJAX-контроллер изменения пароля Пользователя
   */
  public function chenPasswUser()
  {
    $data['new-password'] = $this->request->input('newPassword');
    $data['password-confirm'] = $this->request->input('passwordConfirm');
    $data['user_id'] = $this->request->input('user_id');

    if ($data['new-password'] == $data['password-confirm']) {
      $pst = Users::admChengeUserPassw($data);
      print json_encode(['st' => $pst]);
    }
  }


  /**
   * POST-контроллер создания Пользователя
   *
   * @param userAddPost $request
   */
  public function usersAddAPI(userAddPost $request)
  {
    $this->request = $request;
    $pst = 0;
    $data['name'] = $this->request->input('name');
    $data['email'] = $this->request->input('email');
    $data['status'] = $this->request->input('level_user');
    $data['password'] = $this->request->input('password');
    $data['password_confirmation'] = $this->request->input('password_confirmation');
    $data['users_phone'] = $this->request->input('users_phone');
    $data['users_deck'] = $this->request->input('users_deck');

    $data['gov_group'] = $this->request->input('gov_group');

    $data['gov_group_root'] = $this->request->input('gov_group_root');
    $data['gov_group_master'] = $this->request->input('gov_group_master');

    $data['users_cont_phone'] = $this->request->input('users_cont_phone');

    if ($this->request->user()->status >= $data['status'] &&
          $this->request->user()->email != $data['email'] &&
         ((int)$data['users_phone'] >= 4000 || (int)$data['users_phone']==0)
       ) {
      $pst = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'status' => $data['status'],
        'password' => Hash::make($data['password']),
      ]);

      if ((int)$pst->id > 0) {
        Users::admChenLevelUser((int)$pst->id, (int)$data['status']);
        Users::admAddUserInfo($data, (int)$pst->id);
      }
    }

    print json_encode(['st' => $pst->id]);
  }

  public function operatorIn()
  {
    $id = (int)$this->request->input('id');
    $minutes = Carbon::now()->addMinutes(5);

    Users::checkLogin($id, 'online', '', $_SERVER['REMOTE_ADDR']);
    Cache::forget('aut' . $id);
    Cache::add('online' . $id, time(), $minutes);

    $phone = Users::getUsersData($id);
    if( count($phone) ) {
      Artisan::call('operatorStatus:start ' . $phone[0]->users_phone);
    }
  }

  public function pongOperatorOut()
  {
    $user_id = (int)$this->request->input('user_id');
    $user_time = $this->request->input('user_time');
    Users::pongOperatorModel($user_id, $user_time);
    return $user_id;
  }

  public function pongOperator()
  {
    $user_id = (int)$this->request->input('user_id');
    $user_time = $this->request->input('user_time');
    Users::pongOperatorModel($user_id, $user_time);

    $phone = Users::getUsersData($user_id);
    if( count($phone) ) {



      Artisan::call('operatorStatus:start ' . $phone[0]->users_phone);
    }

    return $phone[0]->users_phone;
  }

  public function getPermissionGroup()
  {
    return Users::permissionGroup();
  }

  public function whiteIPAdd()
  {
    return Users::addWhiteIp(['ip' => $this->request->input('ip')]);
  }


  public function whiteIPDel()
  {
    return Users::removeWhiteIp(['ip' => (int)$this->request->input('id')]);
  }


}
