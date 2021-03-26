<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ExpiresSession
{
	const THIRTY_MINUTES = 1800;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$now = now()->getTimestamp();
		$lastAcitivity = $request->session()->get('last_activity', null);

		if (!$lastAcitivity && Auth::check()) {
			$request->session()->put('last_activity', $now);
			return $next($request);
		}

		if (!$lastAcitivity) return $next($request);

		$inactivityTime = $now - $lastAcitivity;
		if ($inactivityTime >= self::THIRTY_MINUTES) {
			$request->session()->forget('last_activity');
			$url = $request->path();
			return redirect($url)->with('session-expires-message', 'Sessão expirada por invatividade...');
		}

		$request->session()->put('last_activity', $now);
		return $next($request);

		// $atualTime = time();
		// if ($request->session()->has('last_activity')) {
		// 	$lastAcitivity = $request->session()->get('last_activity');
		// 	if ($atualTime - $lastAcitivity >= 1800) {
		// 		$request->session()->forget('last_activity');
		// 		$url = $request->path();
		// 		return redirect($url)->with('session-expires-message', 'Sessão expirada por invatividade...');
		// 	} else {
		// 		$request->session()->put('last_activity', $atualTime);
		// 	}
		// } else {
		// 	if (Auth::check()) {
		// 		$request->session()->put('last_activity', $atualTime);
		// 	}
		// }
		// return $next($request);
	}
}
