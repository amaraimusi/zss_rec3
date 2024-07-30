<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/', 'App\Http\Controllers\DashboardController@index');
Route::get('dashboard', 'App\Http\Controllers\DashboardController@index');
Route::get('home', 'App\Http\Controllers\DashboardController@index');
Route::get('logout', 'App\Http\Controllers\DashboardController@logout');

Auth::routes(); // 認証関連



// ネコ管理画面
Route::get('neko', 'App\Http\Controllers\NekoController@index');
Route::post('neko/reg_action', 'App\Http\Controllers\NekoController@regAction');
Route::get('neko/create', 'App\Http\Controllers\NekoController@create');
Route::post('neko/store', 'App\Http\Controllers\NekoController@store');
Route::get('neko/show', 'App\Http\Controllers\NekoController@show');
Route::get('neko/edit', 'App\Http\Controllers\NekoController@edit');
Route::post('neko/update', 'App\Http\Controllers\NekoController@update');
Route::post('neko/auto_save', 'App\Http\Controllers\NekoController@auto_save');
Route::post('neko/disabled', 'App\Http\Controllers\NekoController@disabled');
Route::post('neko/destroy', 'App\Http\Controllers\NekoController@destroy');
Route::get('neko/csv_download', 'App\Http\Controllers\NekoController@csv_download');
Route::post('neko/ajax_pwms', 'App\Http\Controllers\NekoController@ajax_pwms');

// ネコ種別管理画面
Route::get('neko_type', 'App\Http\Controllers\NekoTypeController@index');
Route::get('neko_type/create', 'App\Http\Controllers\NekoTypeController@create');
Route::post('neko_type/store', 'App\Http\Controllers\NekoTypeController@store');
Route::get('neko_type/show', 'App\Http\Controllers\NekoTypeController@show');
Route::get('neko_type/edit', 'App\Http\Controllers\NekoTypeController@edit');
Route::post('neko_type/update', 'App\Http\Controllers\NekoTypeController@update');
Route::post('neko_type/auto_save', 'App\Http\Controllers\NekoTypeController@auto_save');
Route::post('neko_type/disabled', 'App\Http\Controllers\NekoTypeController@disabled');
Route::post('neko_type/destroy', 'App\Http\Controllers\NekoTypeController@destroy');
Route::get('neko_type/csv_download', 'App\Http\Controllers\NekoTypeController@csv_download');


// 日誌管理画面
Route::get('diary', 'App\Http\Controllers\DiaryController@index');
Route::post('diary/reg_action', 'App\Http\Controllers\DiaryController@regAction');
Route::get('diary/create', 'App\Http\Controllers\DiaryController@create');
Route::post('diary/store', 'App\Http\Controllers\DiaryController@store');
Route::get('diary/show', 'App\Http\Controllers\DiaryController@show');
Route::get('diary/edit', 'App\Http\Controllers\DiaryController@edit');
Route::post('diary/update', 'App\Http\Controllers\DiaryController@update');
Route::post('diary/auto_save', 'App\Http\Controllers\DiaryController@auto_save');
Route::post('diary/disabled', 'App\Http\Controllers\DiaryController@disabled');
Route::post('diary/destroy', 'App\Http\Controllers\DiaryController@destroy');
Route::get('diary/csv_download', 'App\Http\Controllers\DiaryController@csv_download');
Route::post('diary/ajax_pwms', 'App\Http\Controllers\DiaryController@ajax_pwms');


// 教え画面
Route::get('knowledge', 'App\Http\Controllers\KnowledgeController@index');
Route::post('knowledge/reg_action', 'App\Http\Controllers\KnowledgeController@regAction');
Route::get('knowledge/create', 'App\Http\Controllers\KnowledgeController@create');
Route::post('knowledge/store', 'App\Http\Controllers\KnowledgeController@store');
Route::get('knowledge/show', 'App\Http\Controllers\KnowledgeController@show');
Route::get('knowledge/edit', 'App\Http\Controllers\KnowledgeController@edit');
Route::post('knowledge/update', 'App\Http\Controllers\KnowledgeController@update');
Route::post('knowledge/auto_save', 'App\Http\Controllers\KnowledgeController@auto_save');
Route::post('knowledge/disabled', 'App\Http\Controllers\KnowledgeController@disabled');
Route::post('knowledge/destroy', 'App\Http\Controllers\KnowledgeController@destroy');
Route::get('knowledge/csv_download', 'App\Http\Controllers\KnowledgeController@csv_download');
Route::post('knowledge/ajax_pwms', 'App\Http\Controllers\KnowledgeController@ajax_pwms');


// 教えカテゴリ画面
Route::get('kl_category', 'App\Http\Controllers\KlCategoryController@index');
Route::post('kl_category/reg_action', 'App\Http\Controllers\KlCategoryController@regAction');
Route::get('kl_category/create', 'App\Http\Controllers\KlCategoryController@create');
Route::post('kl_category/store', 'App\Http\Controllers\KlCategoryController@store');
Route::get('kl_category/show', 'App\Http\Controllers\KlCategoryController@show');
Route::get('kl_category/edit', 'App\Http\Controllers\KlCategoryController@edit');
Route::post('kl_category/update', 'App\Http\Controllers\KlCategoryController@update');
Route::post('kl_category/auto_save', 'App\Http\Controllers\KlCategoryController@auto_save');
Route::post('kl_category/disabled', 'App\Http\Controllers\KlCategoryController@disabled');
Route::post('kl_category/destroy', 'App\Http\Controllers\KlCategoryController@destroy');
Route::get('kl_category/csv_download', 'App\Http\Controllers\KlCategoryController@csv_download');
Route::post('kl_category/ajax_pwms', 'App\Http\Controllers\KlCategoryController@ajax_pwms');



// ユーザー管理画面
Route::get('user_mng', 'App\Http\Controllers\UserMngController@index');
Route::get('user_mng/create', 'App\Http\Controllers\UserMngController@create');
Route::post('user_mng/store', 'App\Http\Controllers\UserMngController@store');
Route::get('user_mng/show', 'App\Http\Controllers\UserMngController@show');
Route::get('user_mng/edit', 'App\Http\Controllers\UserMngController@edit');
Route::post('user_mng/update', 'App\Http\Controllers\UserMngController@update');
Route::post('user_mng/auto_save', 'App\Http\Controllers\UserMngController@auto_save');
Route::post('user_mng/disabled', 'App\Http\Controllers\UserMngController@disabled');
Route::post('user_mng/destroy', 'App\Http\Controllers\UserMngController@destroy');
Route::get('user_mng/csv_download', 'App\Http\Controllers\UserMngController@csv_download');

// AWSテスト
Route::get('aws', 'App\Http\Controllers\AwsController@index');
Route::post('aws_post', 'App\Http\Controllers\AwsController@index');
Route::get('aws/test2', 'App\Http\Controllers\AwsController@test2');

Route::get('demo', 'App\Http\Controllers\DemoController@index');
Route::post('demo/spa_demo', 'App\Http\Controllers\DemoController@spa_demo');

