<?php

namespace App\Http\Controllers;

use FCM;
use App\Cases;
use App\Users;
use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use App\Http\Controllers\CaseController;
use LaravelFCM\Message\PayloadDataBuilder;
use App\Http\Controllers\FirebaseController;
use LaravelFCM\Message\PayloadNotificationBuilder;

class MessageController extends Controller
{
    // public function receiveMessage(){
    //     $resultCode = 3000;
    //     $message = "";
    //     $data = array();
    //     try{
    //         $jsonData = json_decode(file_get_contents('php://input'),true);
    //         if(isset($jsonData)){
    //             $id = str_replace("+84","0",$jsonData['phone']);
    //             $userMessage = $jsonData['message'];
    //             $longitude = $jsonData['longitude'];
    //             $latitude = $jsonData['latitude'];
    //             $type = $jsonData['type'];
    //             // $radius = $jsonData['radius'];
                
    //             $user = Users::find($id);
    //             $caseController = new CaseController();
    //             $case = $caseController->createCase($id, $longitude, $latitude, $userMessage, $type);
                
    //             $controller = new FirebaseController();
    //             $knightList = $controller->index(200, $longitude, $latitude);
    //             //Send message to Knight
    //             $this->sendMessage($case, $knightList);
    //             $resultCode = 200;
    //             $data = $case;
    //         }
    //     }catch(Exception $e){
    //         $resultCode = 404;
    //         $message = $e->getMessage();
    //     }
    //     finally{
    //         return response()->json([
    //             'result' => $resultCode,
    //             'message' => $message,
    //             'data' => $data,
    //         ]);
    //     }
        
    // }

    public function sendMessage($case, $knightList){
        // dd($knightList);
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Xảy ra Sự cố');
        $notificationBuilder->setBody('Đã có sự cố')
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $case = $case->where("id",$case->id)->with('user')->first();
        // dd($case);
        // $user = Users::find($case->citizenId);
        // if($user->role == 2 && $user->id == $case->knightConfirmId){
        //     $case['inCase'] = true;
        // }else{
        //     $case['inCase'] = false;
        // }
        $dataBuilder->addData(['item' => $case]);
        
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // $token = "cd_f6oqOOhM:APA91bEM62sYFugW3Gxu5kLUCGnawXbZpbz0ZPanhAIUiyMEoz0w9pMM8AZLS2NuCW9Ht2I3gHGW_hpQAjQzok_QAKdAdmaOjkQsga6q9G-izGaEo-QFgJXY34m2Y96xbestr5v7fIyC";
        $tokens = array();
        foreach ($knightList as $knight) {
            $id = $knight['id'];
            $k = Users::find(str_replace('+84','0',$id));
            if(!empty($k->token)){
                if(!in_array($k->token, $tokens)){
                    array_push($tokens,$k->token);
                }
            }           
        }
        // array_push($token,"djVTbMvNrLI:APA91bHAhY0Y0ZAnzVzrX3eWqveogdxpa2j2vcPLVcwKUdAVzfx735TgxXGVyj2gl7z9EavgGoPajN8YCr2rTgHAOG9k8pj52V5JdsZHrAc2EtXO6SruPwIY04xnx16Gd7U07EmfGFy-");
        foreach ($tokens as $token) {
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
        }
        if(isset($downstreamResponse)){
            return $downstreamResponse->numberSuccess();
        }
        
        // dd($tokens);
        // $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        
        // $token = 'd3NfHPDPLUg:APA91bHyADW0w7qceVMPM0vsdsejHNGDxsdugGVXfr5Rb14KCSPJQl2mHqopojCKz0rBeDA8zsGzokIKIAvzUTda6zifC700vWnlbmF_y9QHnTzaPuxZzaEaiUH19bW41pKxIxAUFt2X';
        // FCM::sendTo($token, $option, $notification, $data);
        return 0;
    }

    public function sendMessageToCitizen($case, $knightId, $citizenToken, $type = 'join'){
        $case = $case->where("id",$case->id)->with('user')->first();
        $knight = Users::find($knightId);

        $optionBuilder = new OptionsBuilder();
        $dataBuilder = new PayloadDataBuilder();
        $notificationBuilder = new PayloadNotificationBuilder('Hiệp Sĩ '.$knight->name.' Tham Gia');
        

        $optionBuilder->setTimeToLive(60*20);
        if($type == 'join'){
            $notificationBuilder->setBody('Hiệp sĩ '.$knight->name.' đã tham gia xử lí sự cố')
                                ->setSound('default');     
        }elseif($type == 'leave'){
            $notificationBuilder->setBody('Hiệp sĩ '.$knight->name.' đã tạm hoãn xử lí sự cố')
                                ->setSound('default');
        }
        $dataBuilder->addData(['item' => $case]);
        

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $citizenToken;
        // dd($token);
        if(!empty($token)){
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            return $downstreamResponse->numberSuccess();
        }
        
        return 0;
    }

    public function sendAlertToCitizen($citizenToken, $action){
        $optionBuilder = new OptionsBuilder();
        $dataBuilder = new PayloadDataBuilder();

        
        $optionBuilder->setTimeToLive(60*20);
        if($action == true){
            $notificationBuilder = new PayloadNotificationBuilder('Bạn đang đi vào đoạn đường nguy hiểm');
            $notificationBuilder->setBody('Bấm để xem chi tiết trên bản đồ')
                            ->setSound('default'); 
        }else{
            $notificationBuilder = new PayloadNotificationBuilder('Bạn đã ra khỏi đoạn đường nguy hiểm');
            $notificationBuilder->setBody('Bấm để xem chi tiết trên bản đồ')
                            ->setSound('default'); 
        }
            
        
        $dataBuilder->addData(['item' => []]);
        

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $citizenToken;
        // dd($token);
        if(!empty($token)){
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            return $downstreamResponse->numberSuccess();
        }
        
        return 0;
    }
}
