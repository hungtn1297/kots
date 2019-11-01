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

Route::post('login','OtherController@checkLogin');
Route::post('findKnight','KnightController@findKnight');

Route::post('getCase','CaseController@get');
Route::post('sendCase','MessageController@receiveMessage');
Route::post('confirmCase', 'CaseController@changeCaseStatus');
Route::post('closeCase','CaseController@changeCaseStatus');
Route::post('pendingCase','CaseController@changeCaseStatus');

Route::post('findUser','UserController@findUser');

Route::post('updateProfile','UserController@updateProfile');
Route::post('createProfile','UserController@createProfile');
Route::post('removeToken','UserController@removeToken');

Route::get('getNews','NewsController@get');

Route::get('getPoliceContact','PoliceContactController@get');

Route::post('getTeamInfoByKnightId','KnightTeamController@getTeamInfoByKnightId');