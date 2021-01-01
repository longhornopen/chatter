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
        // FIXME do not commit
        $request->attributes->set('course_user_id', 1);
        //$request->attributes->set('course_user_id', $request->session()->get('course_user_id'));

        return $next($request);
    }
}
