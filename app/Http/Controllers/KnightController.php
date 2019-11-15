<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\CaseDetail;
use App\Cases;
use App\Knight;

class KnightController extends Controller
{
    private $NOT_APPROVED = 0;
    private $FREE = 1;
    private $INCASE = 2;
    private $BANNED = 0;
    private $ACTIVE = 1;

    public function get(){
        // Function dùng để lấy danh sách các hiệp sĩ
        $listKnights = Users::where('role',2)
                            ->where('status','!=',0)
                            ->get();
        // dd($listKnights);
        return view('admin/Knight/ListKnight')->with(compact('listKnights'));
    }

    public function viewProfile(Request $request){
        // Function dùng để xem thông tin chi tiết của hiệp sĩ
        $knights = Users::where('role',2)
                        ->where('id', $request->id)
                        ->get();
        // dd($knight);
        if($knights->count()>0){
            $knight = $knights[0];
            $caseDetails = CaseDetail::where('knightId', $knight->id)->get();
            $knight['caseDetail']  = $caseDetails;
            return view('admin/Knight/ProfileKnight')->with(compact('knight'));
        }else{
            $error = "Không tìm thấy thông tin chi tiết của hiệp sĩ này";
            return view('admin/error')->with(compact('error'));
        }
    }

    public function disable(Request $request){
        // Function dùng để điều chỉnh trạng thái của Citizen
        // Nếu trạng thái là disable thì sẽ trở thành available
        // Nếu trạng thái là availabel sẽ trở thành disable
        $knight = Users::where('role',2)
                        ->where('id', $request->id)
                        ->get();
        if($knight->count()>0){
            $knight = $knight[0];
            if($knight->isDisable == $this->BANNED){
                $knight->isDisable = $this->ACTIVE;
            }else{
                $knight->isDisable = $this->BANNED;
            }
            $knight->save();
            return redirect()->action('KnightController@get');
        }else{
            $error = "Không tìm thấy thông tin chi tiết của hiệp sĩ này";
            return view('admin/error')->with(compact('error'));
        }
    }

    public function findKnight(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);
            if(isset($json)){
                $id = str_replace("+84","0",$json['phone']);
                $knight = Knight::where('id',$id)
                            ->where('role',2)->first();
                if(isset($knight)){
                    $resultCode = 200;
                    $message = "Knight exist";
                    $data = $knight;
                }else{
                    $resultCode = 404;
                    $message = "Not found Knight";
                }
            }else{
                $resultCode = 3000;
                $message = "Đã xảy ra lỗi";
            }
        }catch(Exception $e){
            $resultCode = 3000;
            $message = $e->getMessage();
        }
        finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }
    }

    public function joinCase($knightId, $caseId){
        $knightIsInAnyCase = CaseDetail::where('knightId', $knightId)
                                        ->where('isLeave', 0)
                                        ->where('status', 1)
                                        ->latest()
                                        ->first();
        
        $knight = Users::find($knightId);

        $knightInCase = CaseDetail::where('caseId', $caseId)
                                    ->where('knightId',$knightId)
                                    ->first();
        $messageController = new MessageController();


        //Kiểm tra Hiệp sĩ có đang tham gia sự cố nào không?
        if(isset($knightIsInAnyCase)){
            $case = Cases::find($knightIsInAnyCase->caseId);
            $status = $case->status;
            //Nếu sự cố đang được xử lí và Hiệp sĩ vẫn còn tham gia trong sự cố
            if(($status == 1 && $knightIsInAnyCase->status == 1)|| $knight->status == $this->INCASE){
                return 'INCASE';
            }
        }

        //Kiểm tra Hiệp sĩ đã tham gia vào Sự cố đó hay chưa?
        if(!isset($knightInCase)){
            $case = Cases::find($caseId);
            $citizen = Users::find($case->citizenId);
            $caseDetail = new CaseDetail();
            $caseDetail->knightId = $knightId;
            $caseDetail->caseId = $caseId;
            $caseDetail->status = 1;
            $caseDetail->save();

            $case->status = 1;
            $case->save();

            // dd($knightId);
            $this->changeKnightStatus($knightId, $this->INCASE);

            $messageController->sendMessageToCitizen($case, $knightId, $citizen->token, $type = 'join');
            return $caseDetail;
        }elseif(isset($knightInCase) && $knightInCase->isLeave == 1){
            return 'ALREADY LEAVED';
        }

    }

    public function leaveCase($knightId, $caseId){
        $caseDetail = CaseDetail::where('knightId', $knightId)
                                ->where('caseId', $caseId)
                                ->first();
        $caseDetail->status = 0;
        $caseDetail->isLeave = 1;
        $caseDetail->save();

        $knight = Knight::find($knightId);
        $knight->status = $this->FREE;
        $knight->save();
        return true;
    }

    public function changeKnightStatus($knightId, $status){
        
        $knight = Users::where('id',$knightId)
                    ->where('role',2)
                    ->first();
        if(isset($knight)){
            $knight->status = $status;
            $knight->save();
        }else{
            $knight = null;
        }
        
        return $knight;
    }

    public function changeKnightStatusAPI(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];
        
        $json = json_decode(file_get_contents('php://input'), true);
        $knightId = str_replace('+84','0',$json['phone']);
        $status = $json['status'];
        $action = $json['action'];
        $knight = Users::where('id',$knightId)
                    ->where('role',2)
                    ->first();
        
        if(isset($knight)){
            if($action == 'join'){ //Request join team
                if($status == 0){ //Ignore join
                    $knight->team_id = null;
                }
            }elseif ($action == 'leave') { //Request leave
                if($status == 0){ //Accept leave
                    $knight->team_id = null;
                }
            }
            $knight->status = $status;
            $knight->save();
            $resultCode = 200;
            $message = 'SUCCESS';
            $data = $knight;
        }
        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);
        
    }

    public function confirmCase($knightId, $caseId){
        $caseDetail = CaseDetail::where('caseId', $caseId)
                                ->first();
        // dd($caseDetail);
        if(isset($caseDetail)){
            $case = Cases::find($caseId);
            // dd($case);
            $case->knightConfirmId = $knightId;
            $case->save();
            // dd($case);
        }
    }

    public function getJoincaseTime($knightId, $caseId){
        $caseDetail = CaseDetail::where('knightId', $knightId)
                                ->where('caseId', $caseId)
                                ->first();
        
        return $caseDetail->created_at;
    }

    public function requestLeaveTeam(){

        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        $knightId = str_replace('+84','0',$json['phone']);
        $knight = Users::find($knightId);

        if(isset($knight)){
            $knight->status = 3;
            $knight->save();
            $knight->id = $json['phone'];

            $resultCode = 200;
            $message = 'SUCCESS';
            $data = $knight;
        }

        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);
    }
}
