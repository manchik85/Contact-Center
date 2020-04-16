<?php
namespace App\Http\Middleware;
use Closure;
use Carbon\Carbon;
use App\Models\Adm\Users;
use Auth;
use Cache;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {

            $minutes = Carbon::now()->addMinutes(30);

            if (  !Cache::has('aut' . Auth::user()->id)  ) {
                Cache::put('online' . Auth::user()->id, time(), $minutes);
            }
        }
        return $next($request);
    }
}
