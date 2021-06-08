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
Route::get('/course/{course_id}/summary', [ApiController::class, 'getCourseSummary']);
Route::get('/course/{course_id}/user/{user_id}', [ApiController::class, 'getUser']);
Route::get('/course/{course_id}/posts', [ApiController::class, 'getCoursePosts']);
Route::get('/course/{course_id}/post/{post_id}', [ApiController::class, 'getPost']);
Route::post('/course/{course_id}/post/new', [ApiController::class, 'createPost']);
Route::post('/course/{course_id}/post/{post_id}', [ApiController::class, 'editPost']);
Route::delete('/course/{course_id}/post/{post_id}', [ApiController::class, 'deletePost']);
Route::post('/course/{course_id}/post/{post_id}/pin/{pinned}', [ApiController::class, 'pinPost']);
Route::post('/course/{course_id}/post/{post_id}/lock/{locked}', [ApiController::class, 'lockPost']);
Route::post('/course/{course_id}/comment/new', [ApiController::class, 'createComment']);
Route::post('/course/{course_id}/comment/{comment_id}', [ApiController::class, 'editComment']);
Route::post('/course/{course_id}/comment/{comment_id}/endorse/{endorsed}', [ApiController::class, 'endorseComment']);
Route::post('/course/{course_id}/comment/{comment_id}/mute/{muted}', [ApiController::class, 'muteComment']);
Route::post('/course/{course_id}/comment/{comment_id}/upvote/{upvoted}', [ApiController::class, 'upvoteComment']);

