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

Route::get('/v1/countries/list', 'ApiController@countryList');
Route::get('/v1/categories/country/{countryId}', 'ApiController@mainCategories');
Route::get('/v1/categories/subcategories/{categoryId}', 'ApiController@subcategories');
Route::get('/v1/categories/item/{categoryId}', 'ApiController@category');
Route::get('/v1/monuments/list/{categoryId}', 'ApiController@monumentsList');
Route::get('/v1/monuments/item/{id}', 'ApiController@monument');
