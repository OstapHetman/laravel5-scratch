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

// List articles
Route::get('articles', 'ArticleController@index');

// List Single Article
Route::get('article/{id}', 'ArticleController@show');

// Create new Article
Route::post('article', 'ArticleController@store');

// Create new Update
Route::put('articles', 'ArticleController@store');


// Create new Delete
Route::delete('articles', 'ArticleController@destroy');
