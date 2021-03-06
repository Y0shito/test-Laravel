<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('auth', 'App\Http\Controllers\TestController@auth');
Route::post('auth', 'App\Http\Controllers\TestController@postAuth');
Route::get('userDelete', 'App\Http\Controllers\TestController@userDelete');

Route::get('mypage', 'App\Http\Controllers\TestController@mypage')->middleware('auth');
Route::post('mypage', 'App\Http\Controllers\TestController@logout')->middleware('auth');
Route::get('mypage', 'App\Http\Controllers\ArticleController@myArticles');

Route::get('user/{user}', 'App\Http\Controllers\TestController@userpage');

Route::post('follow', 'App\Http\Controllers\TestController@follow');
Route::post('unfollow', 'App\Http\Controllers\TestController@unFollow');

Route::get('index', 'App\Http\Controllers\TestController@index');
Route::get('index', 'App\Http\Controllers\ArticleController@show');

Route::post('bmAdd', 'App\Http\Controllers\BookmarkController@bmAdd');
Route::post('bmRemove', 'App\Http\Controllers\BookmarkController@bmRemove');

Route::get('article/id/{id}', 'App\Http\Controllers\ArticleController@article');

Route::get('create', 'App\Http\Controllers\ArticleController@create')->middleware('auth');
Route::post('create', 'App\Http\Controllers\ArticleController@toPreview')->middleware('auth');

Route::get('preview', 'App\Http\Controllers\ArticleController@fromPreview')->middleware('auth');

Route::post('add', 'App\Http\Controllers\ArticleController@add');
Route::post('draft', 'App\Http\Controllers\ArticleController@draft');

Route::post('edit', 'App\Http\Controllers\ArticleController@edit')->middleware('auth');
Route::post('re_draft', 'App\Http\Controllers\ArticleController@reDraft')->middleware('auth');

Route::get('result', 'App\Http\Controllers\ArticleController@search');

Route::post('close', 'App\Http\Controllers\ArticleController@close');

Route::post('open', 'App\Http\Controllers\ArticleController@open');

Route::post('delete', 'App\Http\Controllers\ArticleController@delete');

Route::post('update_info', 'App\Http\Controllers\InfoController@updateInfo');

Auth::routes();

//Twitter
Route::get('login/twitter', 'App\Http\Controllers\TestController@getAuth');
Route::get('login/twitter/callback', 'App\Http\Controllers\TestController@callback');
