<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user/self', [ApiController::class, 'getUserSelf']);
Route::get('/course/current/summary', [ApiController::class, 'getCourseSummary']);
Route::get('/course/current/post/{post_id}', [ApiController::class, 'getPost']);
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
