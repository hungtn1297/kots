<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\News;

class NewsController extends Controller
{
    public function crawlNews(Request $request){
        $i = "";
        do {
            if($i==-1){
                $i = -2;
            }
            $response = Curl::to('https://nld.com.vn/truy-na-toi-pham'."$i".'.html')
                        ->returnResponseObject()
                        ->get()->content;
            $domDoc = new \DomDocument();
            @$domDoc->loadHTML($response);

            $htmlResult = new \DomXpath($domDoc);
            $relativePaths = $htmlResult->query('//div[@class="news-info"]/h3/a[@class="title"]/attribute::href');
            $image = $htmlResult->query('//div[@class="cate-list-news "]/ul/li/a/img/attribute::src');
            // dd($relativePaths->length);
            // dd(array($relativePaths, $image));
            for ($i=0; $i < $relativePaths->length; $i++) { 
                $absolutePath = 'https://nld.com.vn'.$relativePaths[$i]->textContent;
                $imagePath = $image[$i]->textContent;
                self::getContent($absolutePath,$imagePath);
            }
            // foreach ($relativePaths as $item) {
            //     $absolutePath = 'https://nld.com.vn'."$item->nodeValue";
            //     echo($absolutePath."<br>");
            //     self::getContent($absolutePath);
            // }
            $i--;
        } while ($relativePaths->length > 0);
        // self::getContent('https://nld.com.vn/truy-na/vi-sao-doi-tuong-nguyen-tran-viet-chap-nhan-ra-dau-thu-de-huong-khoan-hong-20190902170510715.htm');
        return redirect()->action('NewsController@get');
    }

    public function getContent($link, $imagePath){
        // $flag = News::where('unitlink',$link)
        //         ->get();
        // echo($flag->count()."<br>");                
        // if($flag->count()==0){
            $response = Curl::to($link)
                        ->returnResponseObject()
                        ->get()->content;
            $domDoc = new \DomDocument();
            @$domDoc->loadHTML($response);

            $htmlResult = new \DomXpath($domDoc);
            $title = $htmlResult->query('//div[@class="nld-detail clearfix fl w828"]/div[@class="fl w520 mg_r50"]/h1[@class="title-content"]')->item(0);
            // dd($title);
            $flag = News::where('title', $title->textContent)->get();
            if($flag->count() == 0){
                $content = $htmlResult->query('//div[@id="content-id"]')->item(0);
                $subContent = $htmlResult->query('//div[@class="fl w520 mg_r50"]/h2[@class="sapo-detail"]')->item(0);
                // $a = trim($content->textContent);
                // dd($subContent->textContent);
                $news = new News();
                $news->title = (string) str_replace(array("\n","\r"),'',strip_tags(trim($title->textContent)));
                $news->image = $imagePath;
                // $news->content = $content->ownerDocument->saveHTML($content);
                $news->content = (string) str_replace(array("\n","\r"),'',strip_tags(trim($content->textContent))); 
                // dd(strval(strip_tags(trim($content->textContent))));
                $news->unitlink = $link;
                $news->source = "https://nld.com.vn";
                $news->subContent = (string) str_replace(array("\n","\r"),'',strip_tags(trim($subContent->textContent)));
                $news->save();
            }
        // }
    }

    public function get(Request $request){
        $resultCode = 3000;
        $message = "";
        $data = array();
        
        
        if($request->is('api/*')){
            $news = News::get();

            $resultCode = 200;
            $message = "Success";
            $data = $news;

            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }else{
            $listNews = News::get();
            return view('admin/News/ListNews')->with(compact('listNews'));
        }
    }

    public function create(Request $request){
        $title = $request->title;
        $image = $request->image;
        $content = $request->content;
        $id = $request->id;

        if(isset($id)){ //Update
            $news = News::find($id);
        }else{          //Insert
            $news = new News();
        }

        $news->title = $title;
        $news->content = $content;
        if(isset($image)){
            $imageLink = 'images/'.$image->getClientOriginalName();
            $image->move('images',$image->getClientOriginalName());
            $news->image = $imageLink;
        }elseif(!isset($id)){
            $error = "Vui lòng tải hình ảnh lên";
            return view('error')->with(compact('error'));
        }

        $news->save();
        return redirect()->action('NewsController@get');
    }

    public function delete(Request $request){
        $id = $request->id;
        if(isset($id)){
            $news = News::find($id);
            $news->delete();
            return redirect()->action('NewsController@get');
        }else{
            $error = "Không tìm thấy tin tức";
            return view('admin/error')->with(compact('error'));
        }
    }

    public function redirectCrawl(){
        return view('admin/News/CrawlNews');
    }

    public function redirectCreate(){
        return view('admin/News/CreateNews');
    }

    public function redirectEdit(Request $request){
            $news = News::find($request->id);
            return view('admin/News/EditNews')->with(compact('news'));
    }
    
}
