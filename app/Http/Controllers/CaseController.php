<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cases;

class CaseController extends Controller
{
    private $CONFIRM = 1;
    private $SUCCESS = 2;
    private $FAIL = 3;
    private $PENDING = 4;

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
                if($status == $this->CONFIRM){
                    $knightId = $json['phone'];
                    $case->knightConfirmId = $knightId;
                    $case->status = $status;
                    $case->save();             
                }elseif($status == $this->SUCCESS || $status == $this->FAIL){
                    $knightId = $json['phone'];
                    $case->knightCloseId = $knightId;
                    $case->status = $status;
                    $case->endLongitude = $json['longitude'];
                    $case->endLatitude = $json['latitude'];
                    $case->save();
                }elseif($status == $this->PENDING){
                    $case->status = $status;
                    $case->save();
                }
                    $resultCode = 200;
                    $message = "Success";
                    $data = $case;
            }else{
                $message = "Not found case";
            }
        }catch(Exception $e){
            $message = $e->getMessage();
            dd($message);
        }
        finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }
    }

    public function createCase($citizenId, $longitude, $latitude, $message){
        $case = new Cases();
        $case->citizenId = $citizenId;
        $case->startLongitude = $longitude;
        $case->startLatitude = $latitude;
        $case->message = $message;
        $case->status = 0;
        $case->save();
        return $case;
    }

    public function get(){
        $listCases = Cases::get();
        
        return view('admin/Case/ListCase')->with(compact('listCases'));
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
    
}
