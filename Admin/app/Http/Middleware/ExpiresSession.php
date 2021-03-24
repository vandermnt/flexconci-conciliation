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
				//$request->session()->flush();
				//return back()->with('message', 'Sessão expirada por inatividade...');
				$request->session()->put('last_activity', $atualTime);
				$data = $request->path();
				//$request->session()->put('data', $data);
				return redirect($data)->with('data', $data);
				// return redirect('login')->with('message', 'Sessão expirada por invatividade...');
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
