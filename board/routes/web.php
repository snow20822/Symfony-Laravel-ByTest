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

/**
 * 首頁
 */
Route::get('/', [
    'as' => 'board', 'uses' => 'BoardController@index'
]);

/**
 * 新增留言
 */
Route::post('/add', [
    'as' => 'add', 'uses' => 'BoardController@add'
]);

/**
 * 修改留言
 */
Route::get('/update/{id}', [
    'as' => 'update', 'uses' => 'BoardController@update'
]);

/**
 * 修改留言送出
 */
Route::post('/updatePost', [
    'as' => 'updatePost', 'uses' => 'BoardController@updatePost'
]);

/**
 * 回覆留言
 */
Route::get('/reMsg/{id}', [
    'as' => 'reMsg', 'uses' => 'BoardController@reMsg'
]);

/**
 * 回覆留言送出
 */
Route::post('/reMsgPost', [
    'as' => 'reMsgPost', 'uses' => 'BoardController@reMsgPost'
]);

/**
 * 刪除留言
 */
Route::delete('/delete', [
    'as' => 'delete', 'uses' => 'BoardController@delete'
]);