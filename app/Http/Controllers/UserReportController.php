<?php

namespace App\Http\Controllers;

use App\UserReport;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    private $ROLE_KNIGHT = 2;
    private $ROLE_CITIZEN = 1;

    public function reportUser($userId, $reason, $reporterId='', $caseId = ''){
        $userReport = new UserReport();
        $user = Users::find($userId);
        // if($user->role == $this->ROLE_KNIGHT){

        // }elseif ($user->role == $this->ROLE_CITIZEN) {
            
        // }
        $userReport->userId = $userId;
        $userReport->reason = $reason;
        if(!empty($reporterId)){
            $userReport->reporterId = $reporterId;
        }
        if(!empty($caseId)){
            $userReport->caseId = $caseId;
        }
        $userReport->save();
    }
}
