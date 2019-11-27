<?php

use App\Http\Controllers\DangerousStreetController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\PoliceContactController;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('login');
});

Route::post('login', 'OtherController@checkLogin');

Route::get('message','MessageController@sendMessage');

Route::get('/firebase','FireBaseController@welcome');
Route::get('test',function(){
    return view('test');
});
Route::get('time','CitizenController@time');
Route::get('map', function(){
    return view('admin/DangerousStreets/map');
});

Route::prefix('admin')->group(function(){
    Route::prefix('citizen')->group(function(){
        Route::get('list','CitizenController@get');
        Route::get('viewprofile','CitizenController@viewProfile');
        Route::post('disable','CitizenController@disable');
    });
    Route::prefix('knight')->group(function(){
        Route::get('list','KnightController@get');
        Route::get('viewprofile','KnightController@viewProfile');
        Route::post('disable','KnightController@disable');
    });
    Route::prefix('knightTeam')->group(function(){
        Route::get('list','KnightTeamController@getTeam');
        Route::get('create',function(){
            $listKnight = App\Users::where('role', 2)
                                    ->where('team_id', null)
                                    ->where('status', 0)
                                    ->get();
            return view('admin/KnightTeam/CreateKnightTeam')->with(compact('listKnight'));
        });
        Route::post('create', 'KnightTeamController@createTeam');
    });
    Route::prefix('news')->group(function(){
        Route::get('crawl', function(){return view('admin/News/CrawlNews');});
        Route::post('crawl', 'NewsController@crawlNews');
        Route::get('list', 'NewsController@get');
        Route::get('create', function(){return view('admin/News/CreateNews');});
        Route::get('edit',function(Request $request){
            $news = App\News::find($request->id);
            return view('admin/News/EditNews')->with(compact('news'));
        });
        Route::post('create','NewsController@create');
        Route::delete('delete','NewsController@delete');
    });
    Route::prefix('case')->group(function(){
        Route::get('list','CaseController@get');
        Route::get('detail','CaseController@detail');
    });
    Route::prefix('policeContact')->group(function(){
        Route::get('list','PoliceContactController@get');
        Route::get('create', function(){
            return view('admin/PoliceContact/CreatePoliceContact');
        });
        Route::post('create', 'PoliceContactController@create');
        Route::get('edit', function(Request $request){
            $policeContact = App\PoliceContact::find($request->id);
            return view('admin/PoliceContact/EditPoliceContact')->with(compact('policeContact'));
        });
        Route::delete('delete','PoliceContactController@delete');
    });
    Route::prefix('dangerousStreets')->group(function(){
        Route::get('/', 'DangerousStreetController@getDS');
        Route::get('/setDS','DangerousStreetController@setDS');
        Route::delete('/unsetDS','DangerousStreetController@unsetDS');
    });
    Route::prefix('feedback')->group(function(){
        Route::get('/list','FeedbackController@getFeedback');
        Route::get('/detail',function(Request $request){
            $feedback = App\Feedback::with('user')->find($request->id);
            return view('admin/Feedback/DetailFeedback')->with(compact('feedback'));
        });
    });
});

