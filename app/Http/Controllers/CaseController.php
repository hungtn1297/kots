<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cases;
use App\CaseDetail;
use App\Http\Controllers\KnightController;
use DB;

class CaseController extends Controller
{
    private $CONFIRM = 1;
    private $SUCCESS = 2;
    private $FAIL = 3;
    private $PENDING = 4;
    private $NORMAL_CASE = 1;
    private $SOS = 2;
    private $CITIZEN_ROLE = 1;
    private $KNIGHT_ROLE = 2;

    public function changeCaseStatus(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);
            $status = $json['status'];
            $caseId = $json['caseId'];
            $case = Cases::find($caseId);          
            if(isset($case)){
                $flag = true;
                if($status == $this->CONFIRM){
                    $knightId = str_replace("+84","0",$json['phone']);
                    $knightController = new KnightController();
                    DB::beginTransaction();
                    try{
                        $case->knightConfirmId = $knightId;
                        $case->status = $status;
                        $case->save();
                        $caseDetail = $knightController->joinCase($knightId, $case->id);
                        DB::commit();      
                    }catch (Exception $e) {
                        DB::rollBack();
                        $resultCode = 3000;
                        $message = $e->getMessage();
                    }       
                }elseif($status == $this->SUCCESS || $status == $this->FAIL){
                    $knightId = str_replace("+84","0",$json['phone']);
                    $caseDetail = CaseDetail::where('caseId', $caseId)
                                            ->where('knightId',$knightId)->first();
                    if(isset($caseDetail)){
                        $case->knightCloseId = $knightId;
                        $case->status = $status;
                        $case->endLongitude = $json['longitude'];
                        $case->endLatitude = $json['latitude'];
                        $case->save();
                    }else{
                        $flag = false;
                    }
                }elseif($status == $this->PENDING){
                    $case->status = $status;
                    $case->save();
                }
                if($flag){
                    $resultCode = 200;
                    $message = "Success";
                    $data = $case;
                }else{
                    $resultCode = 3000;
                    $message = "Knight not in case";
                }
            }else{
                $resultCode = 404;
                $message = "Not found case";
            }
        }catch(Exception $e){
            $message = $e->getMessage();
            // dd($message);
        }
        finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }
    }

    public function createCase($citizenId, $longitude, $latitude, $message, $type){
        $case = new Cases();
        $case->citizenId = $citizenId;
        $case->startLongitude = $longitude;
        $case->startLatitude = $latitude;
        $case->message = $message;
        $case->type = $type;
        $case->status = 0;
        $case->save();
        return $case;
    }

    public function get(){
        $json = json_decode(file_get_contents('php://input'), true);
        if(isset($json)){
            $id = str_replace("+84","0",$json['phone']);
            $role = $json ['role'];
            if($role == $this->CITIZEN_ROLE){
                //TODO
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
        $caseDetails  = CaseDetail::where('knightId', $knightId)->get();
        $newCases = Cases::where('status',0)
                        ->orWhere('status',1)
                        ->get();
        $case = array();
        $listCaseId = array();
        foreach ($newCases as $newCase) {
            // dd($newCase->case->id);
            $citizenName = $newCase->user->name;
            $newCase['citizenName']  = $citizenName;
            $caseId = $newCase->id;
            if(!in_array($caseId, $listCaseId)){
                array_push($listCaseId, $caseId);
                array_push($case, $newCase);    
                // dd($case);
            }
        }
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
        
        return $case;
    }

    public function form(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);


            $resultCode = 200;
            $message = "Success";
        }catch(Exception $e){
            $message = $e->getMessage();
        }finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }
    }

    public function detail(Request $request){
        $id = $request->id;
        $case = Cases::find($id);
        if(isset($case)){
            // foreach ($case->caseDetail as $detail) {
            //     dd($detail->knight);
            // }
            return view('admin/Case/DetailCase')->with(compact('case'));
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
                                ->first();
        if(isset($caseDetail)){
            return true;
        }else{
            return false;
        }
    }
    
}
