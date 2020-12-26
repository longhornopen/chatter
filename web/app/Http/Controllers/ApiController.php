<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course as CourseResource;
use App\Models\Course;
use App\Models\CourseUser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getUserSelf(Request $request) {
        //FIXME
    }

    public function getCourseSummary(Request $request, $course_id)
    {
        $course_user_id = $request->attributes->get('course_user_id');
        $course_user = CourseUser::findOrFail($course_user_id);
        if ((string)$course_user->course_id !== $course_id) {
            dd("AUTH FAIL"); // FIXME
        }

        return new CourseResource(Course::findOrFail($course_id));
    }
}
