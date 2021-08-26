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

Route::get('/app/{course_user_id}', function($course_user_id) {
    return view('chatter_home', [
        'course_user_id'=>$course_user_id
    ]);
});

//Auth::routes();
Route::post('/lti', [App\Http\Controllers\LtiController::class, 'ltiMessage']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// For local development, it's useful to be able to run this without a full LMS.
// Allow impersonating an existing user ID.
if (env('APP_ALLOW_IMPERSONATION')) {
    Route::get('/demo/impersonate/{id}', function(Request $request, $id) {
        $request->session()->push('course_user_ids', $id);
        return 'Impersonating user id #'.$id.'.  <a href="/app/'.$id.'">Go to app.</a>';
    });
}
