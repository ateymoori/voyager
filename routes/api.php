<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('authors', ['uses' => '\App\Http\Controllers\Voyager\AuthorController@index', 'as' => 'voyager.authors.index']);

Route::get('authors', '\App\Http\Controllers\Voyager\AuthorController@getAll');
Route::get('authors/{id}', '\App\Http\Controllers\Voyager\AuthorController@getByID');

Route::get('stories', '\App\Http\Controllers\Voyager\StoryController@getAll');
Route::get('stories/{id}', '\App\Http\Controllers\Voyager\StoryController@getByID');