<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @param  string|null  ...$guards
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next, $guard = null) {
		if ($guard == "admin" && Auth::guard($guard)->check()) {
			if (!$request->is('admin/logout') && Auth::guard($guard)->check()) {
				return redirect('/admin/dashboard');
			}
		}
		if ($guard == "users" && Auth::guard($guard)->check()) {
			return redirect('/');
		}
		return $next($request);
	}
	/*public function handle(Request $request, Closure $next, ...$guards) {
		$guards = empty($guards) ? [null] : $guards;

		foreach ($guards as $guard) {
			if (Auth::guard($guard)->check()) {
				return redirect(RouteServiceProvider::HOME);
			}
		}

		return $next($request);
	}*/
}
