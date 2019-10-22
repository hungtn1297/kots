<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Cases;
use App\Http\Controllers\CaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class MessageController extends Controller
{
    public function receiveMessage(Request $request){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $jsonData = json_decode(file_get_contents('php://input'),true);
            if(isset($jsonData)){
                $id = str_replace("+84","0",$jsonData['phone']);
                $userMessage = $jsonData['message'];
                $longitude = $jsonData['longitude'];
                $latitude = $jsonData['latitude'];
                $type = $jsonData['type'];
                
                $user = Users::find($id);
                $caseController = new CaseController();
                $case = $caseController->createCase($id, $longitude, $latitude, $userMessage, $type);
                $this->sendMessage($case);
                $resultCode = 200;
                $data = $case;
            }
        }catch(Exception $e){
            $resultCode = 404;
            $message = $e->getMessage();
        }
        finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data,
            ]);
        }
        
    }

    public function sendMessage($case){
        try {
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60*20);

            $notificationBuilder = new PayloadNotificationBuilder('Xảy ra Sự cố');
            $notificationBuilder->setBody('Đã có sự cố')
                                ->setSound('default');

            $dataBuilder = new PayloadDataBuilder();
            $case = $case->where("id",$case->id)->with('user')->first();
            // dd($case);
            $dataBuilder->addData(['item' => $case]);

            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            $token = "cd_f6oqOOhM:APA91bEM62sYFugW3Gxu5kLUCGnawXbZpbz0ZPanhAIUiyMEoz0w9pMM8AZLS2NuCW9Ht2I3gHGW_hpQAjQzok_QAKdAdmaOjkQsga6q9G-izGaEo-QFgJXY34m2Y96xbestr5v7fIyC";

            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

            return $downstreamResponse->numberSuccess();
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
