<?php

namespace App\Http\Controllers;

use App\PoliceContact;
use Illuminate\Http\Request;

class PoliceContactController extends Controller
{
    public function get(Request $request){
        $resultCode = 3000;
        $message = "";
        $data = array();
        
        
        if($request->is('api/*')){
            $policeContact = PoliceContact::get();

            $resultCode = 200;
            $message = "Success";
            $data = $policeContact;

            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }else{
            $listPoliceContacts = PoliceContact::get();
            // dd($listPoliceContacts);
            return view('admin/PoliceContact/ListPoliceContact')->with(compact('listPoliceContacts'));
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
}
