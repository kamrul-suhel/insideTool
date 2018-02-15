<?php

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
    return view('index');
});

Route::get('/pages', 'PageController@index');
Route::model('post', 'App\Post');
Route::get('/posts', 'PostController@index');
Route::get('/posts/{post}', 'PostController@show');
Route::get('/posts/{post}/snapshots/{type}/{metric}', 'PostController@jsonSnapshots')
    ->where('metric', '(likes|loves|wows|hahas|sads|angrys|shares|comments)');
