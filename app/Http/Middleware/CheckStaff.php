<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id == 1 || Auth::user()-> role_id == 2|| Auth::user()-> role_id == 3){
            return $next($request);
        }
        else{
            return redirect()->route('login.login')->with('error','You are not authorized to access this website!');
        }
    }
    public function assign_roles(Request $request)
    {

    }
}
