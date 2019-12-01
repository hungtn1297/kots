<?php

namespace App\Http\Controllers;

use App\UserReport;
use App\Users;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    private $ROLE_KNIGHT = 2;
    private $ROLE_CITIZEN = 1;

    public function reportUser(){
        
        $resultCode = 200;
        $message = 'FAIL';
        $data = [];

        $userReport = new UserReport();

        $json = json_decode(file_get_contents('php://input'), true);
        $userId = str_replace('+84','0',$json['userId']);
        $reason = $json['reason'];
        $user = Users::find($userId);
        // if($user->role == $this->ROLE_KNIGHT){

        // }elseif ($user->role == $this->ROLE_CITIZEN) {
            
        // }
        $userReport->userId = $userId;
        $userReport->reason = $reason;
        if(isset($json['reporterId'])){
            $userReport->reporterId = $json['reporterId'];
        }
        if(!empty($json['caseId'])){
            $userReport->caseId = str_replace('+84','0',$json['reporterId']);
        }
        $result = $userReport->save();
        if($result){
            $resultCode = 200;
            $message = 'SUCCESS';
            $data = $userReport;
        }   
        return $this->returnAPI($resultCode,$message,$data);
    }
}
