<?php

namespace App\Http\Controllers;

use DB;
use App\Cases;
use App\UserReport;
use App\Users;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    private $ROLE_KNIGHT = 2;
    private $ROLE_CITIZEN = 1;

    public function reportUser(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $userReport = new UserReport();

        $json = json_decode(file_get_contents('php://input'), true);
        $userId = str_replace('+84','0',$json['phone']);
        $reason = $json['reason'];
        $user = Users::find($userId);
        // if($user->role == $this->ROLE_KNIGHT){

        // }elseif ($user->role == $this->ROLE_CITIZEN) {
            
        // }
        //Nếu user đã bị banned thì không cần report nữa
        if($user->isAvailable == 0){
            return $this->returnAPI(200,'Tài khoản người dùng đã bị khoá',[])
        }

        //Kiểm tra user ăn mấy sẹo
        $numberReport = UserReport::where('userId',$userId)->count();
        if($numberReport <= 2){
            $userReport->userId = $userId;
            $userReport->reason = $reason;
            if(isset($json['reporter'])){
                $repoter = str_replace('+84','0',$json['reporter']);
                $userReport->reporterId = $repoter;
            }
            if(!empty($json['caseId'])){
                $userReport->caseId = $json['caseId'];
                $case = Cases::find($json['caseId']);
            }
            $result = $userReport->save();
            // Sau khi report đã ăn 3 sẹo, ban acc
            $type = 'report';
            if($numberReport == 2){ 
                $user->isAvailable = 0;
                $type = 'banned';
            }
            if($result == true){
                $resultCode = 200;
                $message = 'SUCCESS';
                $data = $userReport;

                $messageController = new MessageController();
                $messageController->sendMessageToCitizen($case, $repoter, $user->token, $type);
            }
        }elseif($numberReport > 2){
            $resultCode = 200;
            $message = 'Tài khoản người dùng đã bị khoá';
            $data = [];
        }
        return $this->returnAPI($resultCode,$message,$data);
    }

    public function userGetReportInfo(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);

        $id = str_replace('+84','0',$json['phone']);
        $userReports = UserReport::where('userId', $id)->get();

        if($userReports->count() >= 0){
            $resultCode = 200;
            $message = 'SUCCESS';
            $data = $userReports;
        }

        return $this->returnAPI($resultCode, $message, $data);

    }
}
