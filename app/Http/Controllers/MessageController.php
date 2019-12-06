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
        try {
            $case = $case->where("id",$case->id)->with('user')->first();
            // dd($case);
            // $user = Users::find($case->citizenId);
            // if($user->role == 2 && $user->id == $case->knightConfirmId){
            //     $case['inCase'] = true;
            // }else{
            //     $case['inCase'] = false;
            // }
            // foreach ($knightList as $knight) {
            //     if($knight->id == $case->citizenId){
            //         $case['inCase'] = true;
            //     }
            // }
            $case['inCase'] = false;
            $dataBuilder->addData(['item' => $case]);
            
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            // $token = "cd_f6oqOOhM:APA91bEM62sYFugW3Gxu5kLUCGnawXbZpbz0ZPanhAIUiyMEoz0w9pMM8AZLS2NuCW9Ht2I3gHGW_hpQAjQzok_QAKdAdmaOjkQsga6q9G-izGaEo-QFgJXY34m2Y96xbestr5v7fIyC";
            $tokens = array();
            foreach ($knightList as $knight) {
                // dd($knight);
                if(str_replace('+84','0',$knight['id']) != $case->citizenId){
                    $id = $knight['id'];
                    $k = Users::find(str_replace('+84','0',$id));
                    if(!empty($k->token)){
                        if(!in_array($k->token, $tokens)){
                            array_push($tokens,$k->token);
                        }
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
        } catch (\Throwable $th) {
            return 0;
        }
        
    }

    public function sendMessageToCitizen($case, $knightId, $citizenToken, $type = 'join'){
        

        $optionBuilder = new OptionsBuilder();
        $dataBuilder = new PayloadDataBuilder();
        $optionBuilder->setTimeToLive(60*20);
        try {
            $case = $case->where("id",$case->id)->with('user')->first();
            $knight = Users::find($knightId);

            if($type == 'join'){
                $notificationBuilder = new PayloadNotificationBuilder('Hiệp sĩ '.$knight->name.' tham gia');
                $notificationBuilder->setBody('Hiệp sĩ '.$knight->name.' đã tham gia xử lí sự cố')
                                    ->setSound('default');     
            }elseif($type == 'leave'){
                $notificationBuilder = new PayloadNotificationBuilder('Hiệp sĩ '.$knight->name.' rời sự cố');
                $notificationBuilder->setBody('Hiệp sĩ '.$knight->name.' đã rời khỏi sự cố')
                                    ->setSound('default');
            }elseif($type == 'close'){
                $notificationBuilder = new PayloadNotificationBuilder('Hiệp sĩ '.$knight->name.' đóng sự cố');
                $notificationBuilder->setBody('Hiệp sĩ '.$knight->name.' đã đóng sự cố')
                                    ->setSound('default');
            }elseif($type == 'banned'){
                $notificationBuilder = new PayloadNotificationBuilder('Bạn đã bị khoá tài khoản, sau khi hiệp sĩ '.$knight->name. 'cho bạn ăn sẹo');
                $notificationBuilder->setBody('Bạn đã bị khoá tài khoản')
                                    ->setSound('default');
            }elseif($type == 'report'){
                $notificationBuilder = new PayloadNotificationBuilder('Hiệp sĩ '.$knight->name.' vừa đánh sẹo bạn');
                $notificationBuilder->setBody('Bạn vừa ăn sẹo, hãy cẩn thận')
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
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function sendAlertToCitizen($citizenToken, $action){
        $optionBuilder = new OptionsBuilder();
        $dataBuilder = new PayloadDataBuilder();

        
        $optionBuilder->setTimeToLive(60*20);
        if($action == true){
            $notificationBuilder = new PayloadNotificationBuilder('Bạn đang đi vào đoạn đường nguy hiểm');
            $notificationBuilder->setBody('Bấm để xem chi tiết trên bản đồ')
                            ->setSound('default'); 
        }elseif($action == false){
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

    public function sendMessageToLeader($knightToken, $action, $userId = ''){
        $optionBuilder = new OptionsBuilder();
        $dataBuilder = new PayloadDataBuilder();

        
        $optionBuilder->setTimeToLive(60*20);
        if($action == 'joinGroup'){
            $notificationBuilder = new PayloadNotificationBuilder('Có yêu cầu tham gia nhóm');
            $notificationBuilder->setBody('Bấm để xem chi tiết')
                            ->setSound('default');
        }elseif($action == 'leaveGroup'){
            $notificationBuilder = new PayloadNotificationBuilder('Có yêu cầu rời khỏi nhóm');
            $notificationBuilder->setBody('Bấm để xem chi tiết')
                            ->setSound('default'); 
        }
        
        if(!empty($userId)){
            $user = Users::find($userId);
            $dataBuilder->addData(['item' => $user]);
        }
        

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $knightToken;
        // dd($token);
        if(!empty($token)){
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            return $downstreamResponse->numberSuccess();
        }
        
        return 0;
    }

    public function sendMessageToKnight($knightToken, $action){
        $optionBuilder = new OptionsBuilder();
        $dataBuilder = new PayloadDataBuilder();

        
        $optionBuilder->setTimeToLive(60*20);
        switch ($action) {
            case 'acceptLeave':
                $notificationBuilder = new PayloadNotificationBuilder('Chấp thuận yêu cầu rời nhóm');
                break;
            case 'ignoreLeave':
                $notificationBuilder = new PayloadNotificationBuilder('Không chấp thuận yêu cầu rời nhóm');
                break;
            case 'acceptJoin':
                $notificationBuilder = new PayloadNotificationBuilder('Chấp thuận yêu cầu tham gia nhóm');
                break;
            case 'ignoreJoin':
                $notificationBuilder = new PayloadNotificationBuilder('Không chấp thuận yêu cầu tham gia nhóm');
                break;
            
            default:
                # code...
                break;
        }
        $notificationBuilder->setBody('Bấm để xem chi tiết')
                        ->setSound('default'); 
        
        // if(!empty($userId)){
        //     $user = Users::find($userId);
        //     $dataBuilder->addData(['item' => $user]);
        // }
        

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $knightToken;
        // dd($token);
        if(!empty($token)){
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            return $downstreamResponse->numberSuccess();
        }
        
        return 0;
    }

}
