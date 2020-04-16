<?php

namespace App\Http\Controllers\Adm\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\userAcauntPost;
use App\Models\Adm\Users;
use Illuminate\Http\Request;
use function is_null;


class ProfileController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $user = $this->request->user();
    $data = [];
    $data['user'] = $user;
    $data['data_user'] = Users::getUsersData($user->id);
    return view('admin.users.self_profile', $data);
  }


  public function unicEmail()
  {
    if (!is_null($this->request->input('id'))) {
      $resp = Users::admUnicCommonInfo([
        'mail' => $this->request->input('mail'),
        'id' => $this->request->input('id')
      ]);
    } else {

      $resp = Users::admUnicEmail([
        'mail' => $this->request->input('mail')
      ]);
    }

    $st = true;

    if (isset($resp[0]->id) && (int)$resp[0]->id > 0) {
      $st = false;
    }

    print json_encode($st);
  }


  public function verificationSelfPass()
  {
    $resp = Users::checkUsersPass($this->request->input('pass'), $this->request->user()->id);
    $st = false;
    if (isset($resp) && (int)$resp > 0) {
      $st = true;
    }
    print json_encode($st);
  }


  /**
   * POST/AJAX-контроллер изменения основной информации о пользователе
   *
   * @param userAcauntPost $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function chengeCommonInfo(userAcauntPost $request)
  {
    $this->request = $request;

    $data['user_id'] = (int)$this->request->input('userId');

    if ((int)$data['user_id'] == 0) {
      $data['user_id'] = $this->request->user()->id;
    }
    $User = Users::getUserAllInfo((int)$data['user_id']);

    if ($User->status < $this->request->user()->status || $data['user_id'] == $this->request->user()->id) {

      $data['users_name'] = $this->request->input('name');
      $data['users_email'] = $this->request->input('email');
      $data['users_phone'] = $this->request->input('users_phone');

      $data['gov_group'] = $this->request->input('gov_group');
      $data['gov_group_root'] = $this->request->input('gov_group_root');
      $data['gov_group_master'] = $this->request->input('gov_group_master');
      $data['users_cont_phone'] = $this->request->input('users_cont_phone');

      if ($data['users_name'] != false && $data['users_email'] != false) {
        Users::admChengeCommonInfo($data);
      }
    }

    return redirect()->route('admin.my_profile');
  }

  /**
   * POST/AJAX-контроллер изменения Своего пароля
   */
  public function chengeSelfPassw()
  {
    $data['oldPassword'] = $this->request->input('oldPassword');
    $data['new-password'] = $this->request->input('newPassword');
    $data['password-confirm'] = $this->request->input('passwordConfirm');
    $data['user_id'] = $this->request->user()->id;

    if ($data['new-password'] == $data['password-confirm']) {
      $pst = Users::admChengePassw($data);
      print json_encode(['st' => $pst]);
    }
  }


  /**
   * POST/AJAX-контроллер изменения Описания
   */
  public function chengeSelfDescr()
  {
    $data['user_id'] = (int)$this->request->input('user_id');
    $User = Users::getUserName((int)$data['user_id']);
    if ($User->status <= $this->request->user()->status) {
      $data['users_deck'] = $this->request->input('users_deck');
      $pst = Users::admChengeDeckript($data);
      print json_encode(['st' => $pst]);
    }
  }

}
