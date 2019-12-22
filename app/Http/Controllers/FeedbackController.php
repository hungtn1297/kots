<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function sendFeedback(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        if(json_last_error()==0){
            $id = str_replace('+84','0',$json['phone']);
            $content = $json['content'];

            $feedback = new Feedback();
            $feedback->userId = $id;
            $feedback->content = $content;
            if(isset($json['isAnonymous'])){
                $feedback->isAnonymous = $json['isAnonymous'];
            }
            $feedback->save();

            $resultCode = 200;
            $message = 'Success';
            $data = $feedback;
        }else{
            $message = 'JSON: '. json_last_error_msg();
        }
        

        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function getFeedbackById(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        if(json_last_error()==0){
            $id = str_replace('+84','0',$json['phone']);
            $feedback = Feedback::where('userId',$id)->get();
            
            $resultCode = 200;
            $message = 'Success';
            $data = $feedback;
        }else{
            $message = 'JSON: '. json_last_error_msg();
        }
        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);

    }

    public function getFeedback(){
        $listFeedbacks = Feedback::with('user')->get();
        // dd($listFeedbacks);
        return view('admin/Feedback/ListFeedback')->with(compact('listFeedbacks'));
    }

    public function redirectDetail(Request $request){
        $feedback = Feedback::with('user')->find($request->id);
        return view('admin/Feedback/DetailFeedback')->with(compact('feedback'));
    }
}
