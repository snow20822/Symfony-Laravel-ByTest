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

/*Route::get('/', function () {
    return view('index');
});
*/
Route::get('/', [
    'as' => 'board', 'uses' => 'BoardController@index'
]);

Route::post('/add', [
    'as' => 'add', 'uses' => 'BoardController@add'
]);

Route::get('/update/{id}', [
    'as' => 'update', 'uses' => 'BoardController@update'
]);

Route::post('/updatePost', [
    'as' => 'updatePost', 'uses' => 'BoardController@updatePost'
]);

Route::get('/reMsg/{id}', [
    'as' => 'reMsg', 'uses' => 'BoardController@reMsg'
]);

Route::post('/reMsgPost', [
    'as' => 'reMsgPost', 'uses' => 'BoardController@reMsgPost'
]);

Route::delete('/delete', [
    'as' => 'delete', 'uses' => 'BoardController@delete'
]);