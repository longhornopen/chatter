<?php

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

Route::get('/app', function() {
    return view('chatter_home');
});

//Auth::routes();
Route::post('/lti', [App\Http\Controllers\LtiController::class, 'ltiMessage']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// For local development, it's useful to be able to run this without a full LMS.
// Allow impersonating an existing user ID.
if (env('APP_ENV') === 'local') {
    Route::get('/demo/impersonate', function() {
        return "<h3>Impersonate who?</h3>"
            ."<a href='/demo/impersonate/1'>User ID #1</a>"
            ;
    });
    Route::get('/demo/impersonate/{id}', function($id) {
        session(['course_user_id'=>$id]);
        return 'Impersonating user id #'.$id.'.  <a href="/app">Go to app.</a>';
    });
}
