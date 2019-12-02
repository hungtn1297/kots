<?php

use App\Http\Controllers\DangerousStreetController;
use App\Http\Controllers\FeedbackController;
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
Route::post('approvedKnight','KnightController@changeKnightStatusAPI');
Route::post('ignoreKnight','KnightController@changeKnightStatusAPI');
Route::post('requestLeaveTeam','KnightController@requestLeaveTeam');

Route::post('getCase','CaseController@get');
Route::post('sendCase','CaseController@sendCase');
Route::post('confirmCase', 'CaseController@changeCaseStatus');
Route::post('closeCase','CaseController@changeCaseStatus');
Route::post('pendingCase','CaseController@changeCaseStatus');
Route::post('leaveCase', 'CaseController@leaveCase');
Route::post('cancelCase', 'CaseController@changeCaseStatus');

Route::post('findUser','UserController@findUser');
Route::post('updateProfile','UserController@updateProfile');
Route::post('createProfile','UserController@createProfile');
Route::post('removeToken','UserController@removeToken');

Route::get('getNews','NewsController@get');

Route::get('getPoliceContact','PoliceContactController@get');

Route::post('getTeamInfoByKnightId','KnightTeamController@getTeamInfoByKnightId');
Route::post('getTeam','KnightTeamController@getTeam');
Route::post('createTeam','KnightTeamController@createTeam');
Route::post('getWaitingKnight','KnightTeamController@getWaitingKnight');

Route::post('sendFeedback', 'FeedbackController@sendFeedback');
Route::post('getFeedbackById', 'FeedbackController@getFeedbackById');

Route::get('getDS','DangerousStreetController@getDS');
Route::post('alertDS','DangerousStreetController@alertDS');

Route::post('reportUser','UserReportController@reportUser');
Route::post('userGetReportInfo','UserReportController@userGetReportInfo');