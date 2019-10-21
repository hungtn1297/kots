<?php
use Illuminate\Http\Request;
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
    });
});