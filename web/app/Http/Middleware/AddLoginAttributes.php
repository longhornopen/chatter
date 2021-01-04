<?php

namespace App\Http\Middleware;

use App\Exceptions\LoginExpiredException;
use Closure;
use Illuminate\Http\Request;

class AddLoginAttributes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('course_user_id')) {
            throw new LoginExpiredException('Login expired.');
        }
        $request->attributes->set('course_user_id', $request->session()->get('course_user_id'));

        return $next($request);
    }
}
