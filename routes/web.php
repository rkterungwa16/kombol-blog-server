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

Route::get('/', [
    'uses' => 'BlogController@getIndex',
    'as' => 'blog.index'
]);

Route::get('/post', function(){
    return view('blog.post');
});

Route::get('post/{id}', [
    'uses' => 'BlogController@getPost',
    'as' => 'blog.post'
]);

Route::post('create', [
    'uses' => 'BlogController@createPost',
    'as' => 'admin.create'
]);
