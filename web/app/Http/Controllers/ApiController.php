<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course as CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCourseSummary(Request $request, $course_id)
    {
        // FIXME have middleware to set request attributes
        $request->attributes->set('course_user_id', $request->session()->get('course_user_id'));

        return new CourseResource(Course::findOrFail($course_id));
    }
}
