<?php

namespace App\Http\Controllers;

use App\Exceptions\LoginExpiredException;
use App\Models\Course;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ContentController extends Controller
{
    public function getHome($course_id)
    {
        return view('chatter_home', [
            'course_id'=>$course_id
        ]);
    }

    public function getWelcome(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
        return view('chatter_welcome', [
            'course'=>$course,
            'user' => $request->get('user'),
        ]);
    }

    public function postWelcome(Request $request, $course_id) {
        if (!$request->session()->has('course_users')) {
            throw new LoginExpiredException('Login expired.');
        }
        $logins = $request->session()->get('course_users');

        if (!array_key_exists($course_id, $logins)) {
            throw new LoginExpiredException("Login mismatch.");
        }

        $course_user = CourseUser::find($logins[$course_id]);
        if ($course_user === null) {
            throw new LoginExpiredException("Login expired.");
        }

        if ($request->get('email') == 'true') {
            $course_user->mail_digest_frequency_minutes = 1440;
        } else {
            $course_user->mail_digest_frequency_minutes = -1;
        }
        $course_user->save();

        return redirect('/course/'.$course_id);
    }

    public function getUnsubscribeConfirm(Request $request, $course_id)
    {
        return view('unsub_confirm', [
            'course_id' => $course_id,
            'user' => $request->get('user'),
            'token' => $request->get('token')
        ]);
    }

    public function postUnsubscribe(Request $request, $course_id)
    {
        $course_user = CourseUser::findOrFail($request->get('user'));
        $token = PersonalAccessToken::findToken($request->get('token'));
        if ($token &&
            $token->tokenable_type==='App\Models\CourseUser' &&
            $token->tokenable_id===$course_user->id &&
            $token->can('digest:unsubscribe')) {

            $course_user->mail_digest_frequency_minutes = -1;
            $course_user->save();

            return view('unsub_result_success', ['course'=>$course_user->course]);
        }

        return view('unsub_result_failure');
    }
}
