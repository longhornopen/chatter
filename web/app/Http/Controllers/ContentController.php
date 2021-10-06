<?php

namespace App\Http\Controllers;

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

            $course_user->mail_digest_frequency = -1;
            $course_user->save();

            return view('unsub_result_success', ['course'=>$course_user->course]);
        }

        return view('unsub_result_failure');
    }
}
