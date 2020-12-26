<?php

namespace App\Http\Middleware;

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
        // FIXME get this from session
        $request->attributes->set('course_user_id', 1);

        return $next($request);
    }
}
