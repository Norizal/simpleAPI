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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'API\UserController@register');
Route::post('login', 'API\UserController@login');




Route::group(['middleware' => 'auth:api'], function(){

    Route::get('getItemTest', 'API\ItemController@getItemTest');
    Route::post('createItem', 'API\ItemController@create');
    Route::post('updateItem/{id}', 'API\ItemController@update');
    Route::get('getItem', 'API\ItemController@getItem');

    
    Route::get('getItemById/{id}', 'API\ItemController@getItemById');
    Route::post('deleteItem/{id}', 'API\ItemController@deleteItem');



});




