<?php

namespace App\Http\Middleware;

use App\Exceptions\LoginExpiredException;
use App\Models\CourseUser;
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
        if (!$request->session()->has('course_users')) {
            throw new LoginExpiredException('Login expired.');
        }
        $logins = $request->session()->get('course_users');
        $request_course_id = $request->header('X-Chatter-CourseID');

        if (!array_key_exists($request_course_id, $logins)) {
            throw new LoginExpiredException("Login mismatch.");
        }

        $course_user = CourseUser::find($logins[$request_course_id]);
        if ($course_user === null) {
            throw new LoginExpiredException("Login expired.");
        }
        $request->attributes->set('course_user', $course_user);

        return $next($request);
    }
}
