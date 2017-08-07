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
    return redirect()->route('dashboard');
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

// Users
Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/add', 'UsersController@addUserForm')->name('users.add');
Route::post('/users/invite', 'UsersController@invite')->name('users.invite');
Route::get('/users/register/{token}', 'UsersController@registerForm')->name('users.registerForm');
Route::post('/users/register/{token}', 'UsersController@register')->name('users.register');
Route::get('/users/edit/{id}', 'UsersController@editForm')->name('users.editForm');
Route::put('/users/edit/{id}', 'UsersController@edit')->name('users.edit');
Route::delete('/users/delete', 'UsersController@deleteUser')->name('users.delete');

// Countries
Route::get('/countries', 'CountriesController@index')->name('countries.index');
Route::get('/countries/set-default/{id}', 'CountriesController@setDefault')->name('countries.setDefault');
Route::get('/countries/add', 'CountriesController@addForm')->name('countries.addForm');
Route::post('/countries', 'CountriesController@add')->name('countries.add');
Route::get('/countries/edit/{id}', 'CountriesController@editForm')->name('countries.editForm');
Route::put('/countries/edit/{id}', 'CountriesController@edit')->name('countries.edit');
Route::delete('/countries/delete', 'CountriesController@delete')->name('countries.delete');

// Categories
Route::get('/categories/list/{country?}', 'CategoriesController@index')->name('categories.index');
Route::get('/categories/view/{id}', 'CategoriesController@view')->name('categories.view');
Route::get('/categories/add', 'CategoriesController@addForm')->name('categories.addForm');
Route::post('/categories/add', 'CategoriesController@add')->name('categories.add');
Route::get('/categories/add/{parentId}', 'CategoriesController@addChildForm')->name('categories.addChildForm');
Route::post('/categories/add/{parentId}', 'CategoriesController@addChild')->name('categories.addChild');
Route::get('/categories/edit/{id}', 'CategoriesController@editForm')->name('categories.editForm');
Route::put('/categories/edit/{id}', 'CategoriesController@edit')->name('categories.edit');
Route::delete('/categories/delete', 'CategoriesController@delete')->name('categories.delete');

// Monuments
Route::get('/monuments/list/{categoryId?}', 'MonumentsController@index')->name('monuments.index');
Route::get('/monuments/view/{id}', 'MonumentsController@view')->name('monuments.view');
Route::get('/monuments/add/{categoryId}', 'MonumentsController@addForm')->name('monuments.addForm');
Route::post('/monuments/add/{categoryId}', 'MonumentsController@add')->name('monuments.add');
Route::get('/monuments/edit/{id}', 'MonumentsController@editForm')->name('monuments.editForm');
Route::put('/monuments/edit/{id}', 'MonumentsController@edit')->name('monuments.edit');
Route::delete('/monuments/delete', 'MonumentsController@delete')->name('monuments.delete');
Route::post('/monuments/upload-image/{id}', 'MonumentsController@uploadImage')->name('monuments.uploadImage');
Route::delete('/monuments/delete-image', 'MonumentsController@deleteImage')->name('monuments.deleteImage');