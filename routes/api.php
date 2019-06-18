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

Route::group(['namespace' => 'Api'], function() {

    Route::get('/shops',  ['uses' => 'ShopController@index']);
    Route::post('/shops',  ['uses' => 'ShopController@search']);

    Route::get('/actions',  ['uses' => 'ActionController@index']);
    Route::post('/actions',  ['uses' => 'ActionController@search']);

    Route::get('/product/{product}',  ['uses' => 'ProductController@index']);
    Route::get('/product-related/{product}',  ['uses' => 'ProductController@related']);

    Route::get('/admin-actions/{action}',  ['uses' => 'AdminActionController@index']);
    Route::post('/admin-actions/{action}',  ['uses' => 'AdminActionController@addProduct']);
    Route::delete('/admin-actions/{action}/{product}',  ['uses' => 'AdminActionController@deleteProduct']);

    Route::get('/city',  ['uses' => 'CityController@index']);
    Route::get('/cities/{city}',  ['uses' => 'CityController@search']);


});