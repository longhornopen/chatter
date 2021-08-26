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
        if (!$request->session()->has('course_user_ids')) {
            throw new LoginExpiredException('Login expired.');
        }
        $request->attributes->set('course_user_ids', $request->session()->get('course_user_ids'));
        $course_user_ids = $request->attributes->get('course_user_ids');
        $request_course_user_id = $request->header('X-Chatter-CourseUserID');

        if (!in_array($request_course_user_id, array_values($course_user_ids))) {
            throw new LoginExpiredException("Login mismatch.");
        }

        $course_user = CourseUser::find($request_course_user_id);
        if ($course_user === null) {
            throw new LoginExpiredException("Login expired.");
        }
        $request->attributes->set('course_user', $course_user);

        return $next($request);
    }
}
