<?php

use Illuminate\Http\Request;
use App\Post;

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

Route::get('articles', function() {
    // If the Content-Type and Accept headers are set to 'application/json', 
    // this will return a JSON structure. This will be cleaned up later.
    return Post::all();
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');


Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'UserController@logout');

    Route::get('test', function(){
        return response()->json(['foo'=>'bar']);
    });
});