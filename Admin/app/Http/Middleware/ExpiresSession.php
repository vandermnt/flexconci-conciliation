<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ExpiresSession
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
		$atualTime = time();
		if ($request->session()->has('last_activity')) {
			$lastAcitivity = $request->session()->get('last_activity');
			if ($atualTime - $lastAcitivity >= 20) {
				$request->session()->put('last_activity', $atualTime);
				$url = $request->path();
				return redirect($url)->with('session-expires-message', 'Sessão expirada por invatividade...');
			} else {
				$request->session()->put('last_activity', $atualTime);
			}
		} else {
			if (Auth::check()) {
				$request->session()->put('last_activity', $atualTime);
			}
		}
		return $next($request);
	}
}
