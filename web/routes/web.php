<?php

use App\Http\Controllers\DemoController;
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

Route::prefix('demo')->group(function() {
    Route::get('/', function () {
        return '<a href="/demo/component_gallery.html">Component Gallery<br><a href="/demo/login">Login as a user</a>';
    });
    Route::get('/login', function() {return view('demo/login', [
        'users'=>\App\Models\CourseUser::all(),
    ]);});
    Route::get('/dologin', function(\Illuminate\Http\Request $request) {
        $user_id = $request->get('id');
        $request->session()->put('course_user_id', $user_id);
        $request->session()->save();
        return redirect('/app');
    });
});

Route::get('/app', function() {return view('chatter_home');});

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
