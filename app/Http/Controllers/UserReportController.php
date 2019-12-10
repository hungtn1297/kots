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
        
        $json = json_decode(file_get_contents('php://input'), true);
        $userId = str_replace('+84','0',$json['phone']);
        $reason = $json['reason'];
        $reporter = str_replace('+84','0',$json['reporter']);
        $caseId = $json['caseId'];
        
        $result = $this->reportUserById($userId, $reason, $reporter, $caseId);
        if($result == true){
            $resultCode = 200;
            $message = 'SUCESSS';
            $data = [];
        }elseif($result == 2){
            $message = 'Tài khoản của người dùng đã bị khoá';
        }
        return $this->returnAPI($resultCode,$message,$data);
    }

    public function reportUserById($userId, $reason, $reporter, $caseId){
        $userReport = new UserReport();
        $user = Users::find($userId);
        $result = false;
        if($user->isDisable == 0){
            return 2;
        }

        //Kiểm tra user ăn mấy sẹo
        $numberReport = UserReport::where('userId',$userId)->count();
        if($numberReport <= 2){
            $userReport->userId = $userId;
            $userReport->reason = $reason;
            $userReport->reporterId = $reporter;
            $userReport->caseId = $caseId;

            $result = $userReport->save();
            // Sau khi report đã ăn 3 sẹo, ban acc
            $type = 'report';
            if($numberReport == 2){ 
                $user->isDisable = 0;
                $type = 'banned';
            }
            if($result == true){
                $case = Cases::find($caseId);
                $messageController = new MessageController();
                $messageController->sendMessageToCitizen($case, $reporter, $user->token, $type);
            }
        }

        return $result;
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
