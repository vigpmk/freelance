<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Laravel\Spark\Spark;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

       if(Spark::developer(Auth::user()->email)){
         return redirect('/settings/companies/'.Auth::user()->currentTeam->id);
       }
       else if(Auth::user()->roleOn(Auth::user()->currentTeam)=='owner'){
        // return redirect('/settings/companies/'.Auth::user()->currentTeam->id);
       }
       else if(Auth::user()->roleOn(Auth::user()->currentTeam)=='member'){
         return redirect('/settings/companies/'.Auth::user()->currentTeam->id);
       }
       return $next($request);
    }
}
