<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MustBeGlobalUser
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
        $isGlobalUser = Auth::user()->USUARIO_GLOBAL === 'S';

        if(!$isGlobalUser) {
            return response()->json([
                'status' => 'erro',
                'mensagem' => 'A operação solicitada requer permissões avançadas.',
            ], 401);
        }

        return $next($request);
    }
}
