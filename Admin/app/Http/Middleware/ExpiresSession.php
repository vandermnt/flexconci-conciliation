<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Ramsey\Uuid\Type\Time;

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
		if ($request->session()->has('last_activity')) {
			$lastAcitivity = $request->session()->get('last_activity');
			$atualTime = time();
			//if()
			//$request->session()->put('interval', $interval);
		} else {
			$request->session()->put('last_activity', time());
		}
		return $next($request);
	}
}
