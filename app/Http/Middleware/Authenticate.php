<?php

namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Redirect;

class Authenticate extends Middleware {
	/**
	 * Get the path the user should be redirected to when they are not authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return string
	 */
	protected $guards = [];

	public function handle($request, Closure $next, ...$guards) {
		$this->guards = $guards;

		return parent::handle($request, $next, ...$guards);
	}

	protected function redirectTo($request) {
		if (!$request->expectsJson()) {
			if (current($this->guards) == 'admin') {
				return route('admin.login');
			}
			return route('front.home');
		}
	}
}