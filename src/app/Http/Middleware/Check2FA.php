<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Check2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Если 2FA включена и сессия не верифицирована
            if ($user->google2fa_enabled && !session('auth.2fa_verified')) {
                // Для некоторых путей пропускаем проверку
                if (!in_array($request->path(), ['two-factor/verify', 'logout', 'two-factor'])) {
                    return redirect()->route('two-factor.verify');
                }
            }
        }

        return $next($request);
    }
}
