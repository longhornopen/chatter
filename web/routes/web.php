<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/course/{course_id}', function($course_id) {
    return view('chatter_home', [
        'course_id'=>$course_id
    ]);
});

//Auth::routes();
Route::post('/lti', [App\Http\Controllers\LtiController::class, 'ltiMessage']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// For local development, it's useful to be able to run this without a full LMS.
// Allow impersonating an existing user ID.
if (env('APP_ALLOW_DEV_ROUTES')) {
    Route::prefix('dev')->group(function() {
        Route::get('/', function() {
            return
                "<a href='/dev/impersonate/1'>Sample Teacher</a><br>" .
                "<a href='/dev/impersonate/2'>Sample Student</a><br>";
        });
        Route::get('/impersonate/{id}', function(Request $request, $course_user_id) {
            $cu = \DB::select('SELECT * FROM course_users where id=?', [$course_user_id])[0];
            $logins = $request->session()->get('course_users', []);
            $logins[$cu->course_id] = $cu->id;
            $request->session()->put('course_users', $logins);
            return 'Impersonating user id #'.$cu->id.'.  <a href="/course/'.$cu->course_id.'">Go to app.</a>';
        });
    });
}
