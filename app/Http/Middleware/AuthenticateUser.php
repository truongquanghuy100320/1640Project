<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('staff_id')) {
            // Nếu không, chuyển hướng người dùng đến trang đăng nhập
            return redirect()->route('login.login')->with('error', 'You must login first');
        }

        // Nếu đã đăng nhập, cho phép truy cập vào trang dashboard
        return $next($request);

    }
}
