<?php

namespace App\Http\Controllers;

use DB;
use App\Cases;
use App\Users;
use App\Criminal;
use App\CaseDetail;
use App\CriminalInCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KnightController;
use App\Http\Controllers\FirebaseController;
use App\UserReport;

class CaseController extends Controller
{
    private $CONFIRM = 1;
    private $SUCCESS = 2;
    private $FAIL = 3;
    private $PENDING = 4;
    private $CANCEL = 5;
    private $FAKE = 6;
    private $NORMAL_CASE = 1;
    private $SOS = 2;
    private $CITIZEN_ROLE = 1;
    private $KNIGHT_ROLE = 2;
    private $FREE = 1;
    private $API_KEY = "AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0";

    public function changeCaseStatus(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        
        $json = json_decode(file_get_contents('php://input'), true);
        $status = $json['status'];
        $caseId = $json['caseId'];
        $case = Cases::find($caseId);
        $knightController = new KnightController();
        $userReportController = new UserReportController();
        // dd(isset($case));     
        if(isset($case)){
            $flag = true;
            DB::beginTransaction();
            if($status == $this->CONFIRM){
                $knightId = str_replace("+84","0",$json['phone']);
                $caseDetail = $knightController->joinCase($knightId, $case->id);
                if($caseDetail == 'INCASE'){
                    $case->delete();
                    $resultCode = 3000;
                    $message = 'Xin vui lòng đóng hoặc rời sự cố đang thực hiện';
                    return self::returnAPI($resultCode, $message, []);
                }elseif ($caseDetail == 'ALREADY LEAVED') {
                    $resultCode = 3000;
                    $message = 'Không thể tham gia sự cố đã rời khỏi';
                    return self::returnAPI($resultCode, $message, []);
                }else{
                    $knightController->confirmCase($knightId, $caseId); //Confirm Case
                    // $case->knightConfirmId = $knightId;
                    $case->status = $status;
                    $flag = $flag && $case->save();
                }
            }elseif($status == $this->SUCCESS || $status == $this->FAIL || $status == $this->FAKE){
                $knightId = str_replace("+84","0",$json['phone']);
                $caseDetail = CaseDetail::where('caseId', $caseId)
                                        ->where('knightId',$knightId)
                                        ->where('isIgnore',0)
                                        ->first();
                if(isset($caseDetail)){
                    $case->knightCloseId = $knightId;
                    $case->status = $status;
                    if($status == $this->SUCCESS || $status == $this->FAIL){
                        $case->endLongitude = $json['longitude'];
                        $case->endLatitude = $json['latitude'];

                        $citizen = Users::find($case->citizenId);
                        $messageController = new MessageController();
                        $messageController->sendMessageToCitizen($case, $knightId, $citizen->token, $type = 'close');
                    }elseif ($status == $this->FAKE) {
                        // dd(1234);
                        $flag = $flag && $userReportController->reportUserById($case->citizenId, 'Gửi thông tin giả', $knightId, $case->id);
                    }
                    $flag = $flag && $case->save();
                    //Release tất cả Hiệp sĩ, set status về bằng free
                    $knightController = new KnightController();
                    $caseDetails = CaseDetail::where('caseId',$case->id)->get();
                    foreach ($caseDetails as $cd) {
                        $knightController->changeKnightStatus($cd->knightId, $this->FREE);
                    }
                }else{
                    $flag = false;
                }
            }elseif($status == $this->PENDING || $status == $this->CANCEL){
                if($status == $this->CANCEL){
                    //Kick all knight
                    $caseDetails = CaseDetail::where('caseId', $case->id)
                                            ->where('isLeave',0)
                                            ->get();
                    if(!empty($caseDetails)){
                        foreach ($caseDetails as $caseDetail) {
                            $caseDetail->status = 0;
                            $knight = Users::find($caseDetail->knightId);
                            $knight->status = 1;
                            $knight->save();
                            $caseDetail->save();
                        }
                    }
                }
                $case->status = $status;
                $case->save();
            }
            if($flag){
                DB::commit();
                $resultCode = 200;
                $message = "Success";
                $case = Cases::find($caseId);
                $data = $case;
                // dd($case);
            }else{
                DB::rollback();
                $message = "Hiệp sĩ không có trong sự cố";
            }
        }else{
            $message = "Không tìm thấy sự cố";
        }
        // dd($data);
        return self::returnAPI($resultCode, $message, $data);
        
    }

    public function createSOS($citizenId, $longitude, $latitude, $message, $type){
        $case = new Cases();
        $case->citizenId = $citizenId;
        $case->startLongitude = $longitude;
        $case->startLatitude = $latitude;
        $case->message = $message;
        $case->type = $type;
        $case->status = 0;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&key=".$this->API_KEY;
        // dd($url);
        $json = json_decode(file_get_contents($url));
        $address = ($json->status=="OK")?$json->results[1]->formatted_address:'';
        $case->address = $address;
        $case->save();

        // $case->key = base64_encode($case->id);
        // $case->save();
        return $case;
    }

    public function createCase($citizenId, $longitude, $latitude, $message, $media, $mediatype, $type){
        $case = new Cases();
        $case->citizenId = $citizenId;
        $case->startLongitude = $longitude;
        $case->startLatitude = $latitude;
        $case->message = $message;
        $case->type = $type;
        $case->status = 0;
        if($mediatype == 'sound'){
            $case->sound = $media;
        }
        if($mediatype =='image'){
            $case->image = $media;
        }
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&key=".$this->API_KEY;
        $json = json_decode(file_get_contents($url));
        $address = ($json->status=="OK")?$json->results[1]->formatted_address:'';
        $case->address = $address;
        $case->save();

        // $case->key = base64_encode($case->id);
        // $case->save();
        return $case;
    }

    public function get(){
        $json = json_decode(file_get_contents('php://input'), true);
        if(isset($json)){
            $id = str_replace("+84","0",$json['phone']);
            $role = $json ['role'];
            if($role == $this->CITIZEN_ROLE){
                $case = $this->getCaseByCitizenId($id);
                // $case['detail'] = $case;
                $data = $case;
            }elseif($role == $this->KNIGHT_ROLE){
                $cases = $this->getCaseByKnightId($id);
                // dd($cases);
                foreach ($cases as $case) {
                    $case['inCase'] = $this->checkKnightInCase($case->id, $id);
                }
                $data = $cases;
            }
            $resultCode = 200;
            $message = "Success";
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
            
        }else{
            $listCases = Cases::get();
            return view('admin/Case/ListCase')->with(compact('listCases'));
        }
    }

    public function getCaseByKnightId($knightId){
        $caseDetails  = CaseDetail::where('knightId', $knightId)
                                ->where('isIgnore',0)
                                ->get();
        // dd($caseDetails);
        $newCases = Cases::where('status',0)
                        ->orWhere('status',1)
                        ->get();
        $case = array();
        $listCaseId = array();
        if($newCases->count() > 0){
            foreach ($newCases as $newCase) {
                // dd($this->checkKnightIgnoreCase($knightId, $newCase->id));
                if($this->checkKnightIgnoreCase($knightId, $newCase->id) == false){
                    $citizenName = $newCase->user->name;
                    $newCase['citizenName']  = $citizenName;
                    $caseId = $newCase->id;
                    if(!in_array($caseId, $listCaseId)){
                        array_push($listCaseId, $caseId);
                        array_push($case, $newCase);    
                        // dd($case);
                    }
                }
                // dd($newCase->case->id);
                // dd($newCase->user);
                
            }
        }
        // dd($caseDetails);
        if($caseDetails->count() > 0){
            foreach ($caseDetails as $caseDetail) {
                // dd($caseDetail->case->id);
                $citizenName = $caseDetail->case->user->name;
                $caseDetail->case['citizenName']  = $citizenName;
                $caseId = $caseDetail->case->id;
                if(!in_array($caseId, $listCaseId)){
                    array_push($listCaseId, $caseId);
                    array_push($case, $caseDetail->case);    
                    // dd($case);
                }
            }
        }
        return $case;
    }

    public function getCaseByCitizenId($citizenId){
        $cases = Cases::with('caseDetail')
                    ->where('citizenId', $citizenId)
                    ->get();
        $knightController  = new KnightController();
        foreach ($cases as $case) {
            if(isset($case['knightConfirmId'])){
                $case['knightConfirmId'] = $knightController->getKnightNamePhoneFormat($case['knightConfirmId']);
            }
            if(isset($case['knightCloseId'])){
                $case['knightCloseId'] = $knightController->getKnightNamePhoneFormat($case['knightCloseId']);
            
            }
            foreach ($case->caseDetail as $detail) {
                $detail['knightInfo'] = $knightController->getKnightNamePhoneFormat($detail->knightId);
            }
        }
        if($cases->count()>0){
            return $cases;
        }else{
            return [];
        }
    }

    public function detail(Request $request){
        $id = $request->id;
        $case = Cases::find($id);
        $knight = '';
        $locationList = null;
        $knightController = new KnightController();
        if(isset($case)){
            // foreach ($case->caseDetail as $detail) {
            //     dd($detail->knight);
            // }
            if($case->status == 2){
                $knight = $knightController->getKnightTraveledDistanceInCase($case->id);
                if(isset($knight)){
                    $firebaseController = new FirebaseController();
                    $locationList = $firebaseController->getKnightInTeamLocation($knight->id, $knight->team_id, $case->created_at, $case->updated_at);
                }
            }
            // dd($knight);
           // dd($locationList);
            return view('admin/Case/DetailCase')->with(compact('case','locationList'));
        }else{
            $error = "Không tìm thấy Sự cố";
            return view('admin/error')->with(compact('error'));
        }
    }

    public function getKnightInCase(){
        $resultCode = 3000;
        $message = "";
        $data = array();
    
        $json = json_decode(file_get_contents('php://input'), true);
        if(json_last_error()==0){
            $resultCode = 200;
            $message = "Success";

        }else{
            return response()->json([
                'result' => $resultCode,
                'message' => json_last_error_msg(),
                'data' => $data
            ]);
        }       
    }

    public function checkKnightInCase($caseId, $knightId){
        $caseDetail = CaseDetail::where('caseId', $caseId)
                                ->where('knightId', $knightId)
                                ->where('status',1)
                                ->first();
        if(isset($caseDetail)){
            return true;
        }else{
            return false;
        }
    }

    public function sendCase(){
        $resultCode = 3000;
        $message = "FAIL";
        $data = array();

        $firebaseController = new FirebaseController();
        $knightController = new KnightController();
        $messageController = new MessageController();

        // $knightSendcaseData = '';
        // $knightSendcaseId = '';
        $flag = true;
        $json = json_decode(file_get_contents('php://input'),true);
        if(isset($json)){
            $id = str_replace("+84","0",$json['phone']);
            $userMessage = $json['message'];
            $longitude = $json['longitude'];
            $latitude = $json['latitude'];
            $type = $json['type'];
            $radius = $json['radius'];
            DB::beginTransaction();
            if($type == $this->SOS){
                $case = $this->createSOS($id, $longitude, $latitude, $userMessage, $type);
            }elseif($type == $this->NORMAL_CASE){
                if(isset($json['image'])){
                    $media = $json['image'];
                    $mediatype = 'image';
                }else{
                    $media = $json['sound'];
                    $mediatype = 'sound';
                }
                $case = $this->createCase($id, $longitude, $latitude, $userMessage, $media, $mediatype, $type);
            }
            // dd($case);
            if(!isset($case)){
                $flag = false;
            }
            else{
                $dangerousStreetController = new DangerousStreetController();
                $dangerousStreetController->checkDS($case);
            }
            // dd($flag);
            $user = Users::find($id);
            // dd($user);
            if($user->role == $this->KNIGHT_ROLE){
                $knight = $user;
                if($knightController->joinCase($knight->id, $case->id) == 'INCASE'){
                    $case->delete();
                    return self::returnAPI($resultCode,'Xin vui lòng đóng hoặc rời sự cố đang thực hiện', []);
                };
                $knightController->confirmCase($knight->id, $case->id);
                
                // $knightSendcaseData = $firebaseController->getKnightLocation($knight->id, $knight->team_id);
                // $knightSendcaseId = $knight->id;
            }
            //Get list knight nearly
            $knightList = $firebaseController->getKnightInRadius($radius, $longitude, $latitude);
            //Create new case in firebase
            // dd($knightSendcaseData);
            // $firebaseCase = $firebaseController->createFirebaseCase($case->id, $knightSendcaseId, $knightSendcaseData);
            //Send message to Knight
            $messageController->sendMessage($case, $knightList);
            $case['citizenId'] = $json['phone'];
            if($flag == true){
                DB::commit();
                $resultCode = 200;
                $message = 'SUCCESS';
                $data = $case;
            }
            else{
                DB::rollback();
            }
        }else{
            $message = 'Not found json';
        }
        return $this->returnAPI($resultCode,$message,$data);
    }
    
    public function leaveCase(){
        $resultCode = 3000;
        $message = "FAIL";
        $data = array();
        
        $json = json_decode(file_get_contents('php://input'), true);
        // dd($json);
        if(isset($json)){
            $knightId = str_replace('+84','0',$json['phone']);
            $caseId = $json['caseId'];
            $case = Cases::find($caseId);
            $knightController = new KnightController();
            if($knightController->leaveCase($knightId, $caseId)){
                $messageController = new MessageController();
                $messageController->sendMessageToCitizen($case, $knightId, $case->citizenId, $type = 'leave');

                $resultCode = 200;
                $message = 'Success';
            }
        }
        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function rateCase(){
        $resultCode = 3000;
        $message = 'Có lỗi';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        $case = Cases::find($json['caseId']);
        if(isset($case)){
            if(!isset($case->rate)){ //Sự cố chưa được đánh giá
                $case->rate = $json['rate'];
                $case->notice = $json['notice'];
                $checkCase = $case->save();

                if($checkCase == true){
                    $resultCode = 200;
                    $message = 'Đánh giá thành công';
                    $data = $case;
                }
            }else{
                $message = 'Bạn đã đánh giá sự cố này rồi';
            }
        }else{
            $message = 'Không tìm thấy sự cố';
        }
        return $this->returnAPI($resultCode,$message,$data);
    }

    
    public function reportCase(){
        $resultCode = 3000;
        $message  = 'FAIL';
        $data = [];

        $flag = true;
        $json = json_decode(file_get_contents('php://input'), true);
        $caseId = $json['caseId'];
        $case = Cases::find($caseId);
        DB::beginTransaction();
        if(isset($json['report'])){
            $case->knightReport = $json['report'];
            $flag = $flag && $case->save();
        }
        if(isset($json['criminalName'])){
            $criminal = new Criminal();
            $criminal->name = $json['criminalName'];
            $criminal->age = $json['criminalAge'];
            $criminal->image = $json['criminalImage'];
            $flag = $flag && $criminal->save();

            $criminalInCase = new CriminalInCase();
            $criminalInCase->caseId = $caseId;
            $criminalInCase->criminalId = $criminal->id;
            $flag  = $flag && $criminalInCase->save();

            $data = $criminal;
        }
        if($flag ==true){
            DB::commit();
            $resultCode = 200;
            $message = 'SUCCESS';
        }else{
            DB::rollback();
        }
        return $this->returnAPI($resultCode, $message, $data);

    }

    public function getReportInfo($caseId){
        $reportInfo = UserReport::where('caseId', $caseId)->first();

        $criminalInfo = CriminalInCase::where('caseId',$caseId)->first();

        if(isset($criminalInfo)){
            $criminal = Criminal::find($criminalInfo->criminalId);
            $reportInfo['criminalInfo'] = $criminal;
        }

        $reportInfo = $reportInfo ?? 0;

        return $reportInfo;
    }

    public function ignoreCase(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        $knightId = str_replace('+84','0', $json['phone']);
        $caseId = $json['caseId'];

        $knightController = new KnightController();
        $result = $knightController->ignoreCase($knightId, $caseId);

        if($result == true){
            $resultCode = 200;
            $message = 'SUCCESS';
        }
        return $this->returnAPI($resultCode, $message, $data);
    }

    public function checkKnightIgnoreCase($knightId, $caseId){
        $caseDetail = CaseDetail::where('knightId', $knightId)
                                ->where('caseId', $caseId)
                                ->where('isIgnore', 1)
                                ->first();
        // dd($caseDetail);
        if(!$caseDetail){
            // dd(123);
            return true;
        }
        return false;
    }
}
