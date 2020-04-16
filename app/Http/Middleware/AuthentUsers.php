<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Models\Adm\Users;

class AuthentUsers
{

  /**
   * The authentication factory instance.
   *
   * @var \Illuminate\Contracts\Auth\Factory
   */
  protected $auth;

  /**
   * Create a new middleware instance.
   *
   * @param \Illuminate\Contracts\Auth\Factory $auth
   * @return void
   */
  public function __construct(Auth $auth)
  {
    $this->auth = $auth;
  }

  /**
   * Handle an incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure $next
   * @param string[] ...$guards
   * @return mixed
   *
   * @throws \Illuminate\Auth\AuthenticationException
   */
  public function handle($request, Closure $next, ...$guards)
  {

    //dd(@$request->user()->status);
    $_m = explode('/', $request->url());

//    if (@$request->user()->status == 5) {
      $nonLogout = Users::nonLogout(@$request->user()->id);
//    }


    $ip_array = explode('.', $request->ip());

    if (isset($nonLogout[0]->id) && $nonLogout[0]->id > 0) {
      if ($nonLogout[0]->user_event == 'offline') {
        return redirect('/logout');
      } else if ($nonLogout[0]->user_event == 'aut') {
        return redirect('/aut_page');
      }
    }


    //        if (@Cache::has('aut' . @$request->user()->id)) {
    //            return redirect('/aut_page');
    //        }


    if (!Cache::has('aut' . @$request->user()->id) && !Users::checkAccessMiddleware($request->user(), array_pop($_m))) {
      return redirect()->route('users.common.dashboard');
    }

    if (Users::checkWhiteIp($ip_array) == 0) {
      return redirect('/logout'); //No Access
    }
    $request->session()->put('getLevels', Users::getLevels($request->user()));

    $this->authenticate($guards);

    return $next($request);
  }

  /**
   * Determine if the user is logged in to any of the given guards.
   *
   * @param array $guards
   * @return void
   *
   * @throws \Illuminate\Auth\AuthenticationException
   */
  protected function authenticate(array $guards)
  {
    if (empty($guards)) {
      return $this->auth->authenticate();
    }

    foreach ($guards as $guard) {
      if ($this->auth->guard($guard)->check()) {
        return $this->auth->shouldUse($guard);
      }
    }

    throw new AuthenticationException('Unauthenticated.', $guards);
  }

  /**
   * Get the path the user should be redirected to when they are not authenticated.
   *
   * @param \Illuminate\Http\Request $request
   * @return string
   */
  protected function redirectTo($request)
  {
    return route('/');
  }

}
