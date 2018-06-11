<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/validate_url', ['uses' => 'Api\v1\PostController@validateUrl', 'as' => 'post.validatePost']);
Route::post('v1/get_post', ['uses' => 'Api\v1\PostController@getPost', 'as' => 'post.getPost']);
Route::post('v1/post_comments', ['uses' => 'Api\v1\PostController@getPostComments', 'as' => 'post.getPostComments']);