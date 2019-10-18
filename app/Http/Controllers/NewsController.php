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
        $flag = News::where('unitlink',$link)
                ->get();
        echo($flag->count()."<br>");                
        if($flag->count()==0){
            $response = Curl::to($link)
                        ->returnResponseObject()
                        ->get()->content;
            $domDoc = new \DomDocument();
            @$domDoc->loadHTML($response);

            $htmlResult = new \DomXpath($domDoc);
            $title = $htmlResult->query('//div[@class="nld-detail clearfix fl w828"]/h1[@class="title-content"]')->item(0);
            $content = $htmlResult->query('//div[@id="content-id"]')->item(0);
            $a = $content->ownerDocument->saveHTML($content);
            // dd($title->textContent);
            $news = new News();
            $news->title = $title->textContent;
            $news->image = $imagePath;
            $news->content = $content->ownerDocument->saveHTML($content);
            $news->unitlink = $link;
            $news->save();
        }
    }

    public function get(){
        $listNews = News::get();
        return view('admin/News/ListNews')->with(compact('listNews'));
    }
}
