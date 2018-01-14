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


Route::get('blog/posts', 'BlogController@getAllUserPosts');
Route::post('blog/post', 'BlogController@createPost');
Route::post('post/like/{postId}/', 'BlogController@likePost');
Route::get('post/likes/{postId}/', 'BlogController@getPostLikes');
Route::patch('blog/post/{postId}', 'BlogController@editPost');

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
