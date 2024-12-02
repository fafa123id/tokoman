<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class rootadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard("web")->check()) {
            $roleId = Auth::user()->role_id;
            switch ($roleId) {
                case '0':
                    return redirect('/dashboard');
                case '1':
                    return redirect('/dashboard');
                case '2':
                    return $next($request);
                default:
                    return redirect('/');
            }
        }
        return redirect('/');
    }
}
