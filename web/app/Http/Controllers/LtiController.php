<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use LonghornOpen\LaravelCelticLTI\LtiTool;

class LtiController extends Controller
{
    public function ltiMessage() {
        $tool = new LtiTool();
        $tool->handleRequest();

        if ($tool->getLaunchType() === $tool::LAUNCH_TYPE_LAUNCH) {
            $course = Course::firstOrCreate(
                ['lti_context_pk' => $tool->context->getRecordId()],
                ['name' => $tool->context->title]
            );
            $is_teacher = $tool->userResult->isAdmin() || $tool->userResult->isStaff();
            $course_user = CourseUser::firstOrCreate(
                ['course_id' => $course->id, 'lti_user_id' => $tool->userResult->ltiUserId],
                [
                    'name' => $tool->userResult->fullname,
                    'email' => $tool->userResult->email,
                    'role' => ($is_teacher ? 'teacher' : 'student'),
                ]
            );

            session(
                [
                    'course_user_id' => $course_user->id
                ]
            );

        }

        return redirect('/app');
    }
}
