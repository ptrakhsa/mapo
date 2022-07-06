<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isOrganizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('organizer')->check())
            return $next($request);

        $res = [
            'message' => 'Unauthenticated',
            'code' => 401,
            'action' => [
                'text' => 'go to login',
                'url' => route('organizer.login')
            ]
        ];
        return response(view('errors.exception', compact('res')), 401);
    }
}
