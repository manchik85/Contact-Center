<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\pollUser;
use App\Models\Adm\Users;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Artisan;


class ChatsController extends Controller
{

  public function parserBK()
  {
    $pollDateOff = strtotime(Carbon::now());

    $users = Users::getAllOperatorsForLogout();

    if (count($users) > 0) {

      $pre = [];

      foreach ($users AS $user) {
        if ($user->user_event != 'aut') {
          $pre[$user->users_id]['id']    = $user->users_id;
          $pre[$user->users_id]['phone'] = $user->users_phone;
        } else {
          unset($pre[$user->id]);
        }
        Artisan::call('operatorStatus:stop ' . $user->users_phone);
      }

      if (count($pre) > 0) {

        foreach ($pre AS $id) {
          Users::setLogoutSockets($id['id'], 'offline', date('Y-m-d H:i:s', $pollDateOff));
        }
      }




      broadcast(new pollUser([
        'event' => 'pollDateOff',
        'date' => date('Y-m-d H:i:s', $pollDateOff)
      ]));


    }

    return ['status' => 'success'];
  }
}
