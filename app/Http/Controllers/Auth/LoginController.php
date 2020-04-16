<?php

namespace App\Http\Controllers\Auth;

use App\Models\Adm\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Support\Facades\Artisan;

use Carbon\Carbon;
use Cache;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/dashboard';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function checkUser(Request $request)
  {



    $this->validateLogin($request);

    $strics = Users::getStrike(['email' => $request->email]);

    if (count($strics) >= 5) {
      return 3;
    } else {
      if (Auth::validate(['email' => $request->email, 'password' => $request->password])) {
        return 1;
      } else {
        $id_users_stike = Users::getUserStrike(['email' => $request->email]);
        if (count($id_users_stike) > 0) {
          Users::fixStrike($id_users_stike[0], $request->ip());
        }
        return 0;
      }
    }

  }

  public function setPassword1234546(){

    Users::admChangePas123();
    return 1;

  }

  public function login(Request $request)
  {

    if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')], false)) {

      if (Auth::user() !== null) {

        if ( !Cache::has('online' . Auth::user()->id) && !Cache::has('aut' . Auth::user()->id) ) {

          $minutes = Carbon::now()->addMinutes(30);
          Users::checkLogin(Auth::user()->id, 'online', '', $request->ip());
          Cache::add('online' . Auth::user()->id, time(), $minutes);

          $phone = Users::getUsersData(Auth::user()->id);
          if (count($phone)) {
            Artisan::call('operatorStatus:start ' . (int)$phone[0]->users_phone);
          }
        } else {

          $id_users_stike = Users::getUserStrike(['email' => $request->get('email')]);
          if (count($id_users_stike) > 0) {
            Users::fixStrike($id_users_stike[0], $request->ip());
          }

          Auth::logout();
        }
      }

    }

    return redirect('');
  }


  /**
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function logout()
  {
    if (Auth::user() !== null) {
      Users::checkLogin(Auth::user()->id, 'offline', '', $_SERVER['REMOTE_ADDR']);
      Cache::forget('online' . Auth::user()->id);
      Cache::forget('aut' . Auth::user()->id);

      $phone = Users::getUsersData(Auth::user()->id);
      if (count($phone)) {
        Artisan::call('operatorStatus:stop ' . (int)$phone[0]->users_phone);
      }

    };

    //dd(Auth::user()->users_phone);


    Auth::logout();

    return redirect('/login');
  }


}
