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

Route::resource('/', 'PostController');

Route::resource('usuarios','UserController');
Route::get('/importar_user_api', 'UserController@importarAPI');
Route::get('/users/getall', 'UserController@getUsers');
Route::get('/users/{id}', 'UserController@get');
Route::post('/users/save', 'UserController@save');
Route::delete('/users/delete/{id}', 'UserController@delete');

Route::resource('feed','PostController');
Route::get('/posts/getall', 'PostController@getAll');
Route::get('/users/{id}/posts', 'PostController@getByUser');
