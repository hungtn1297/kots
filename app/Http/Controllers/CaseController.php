<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cases;

class CaseController extends Controller
{
    public function changeCaseStatus(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);
            $knightId = $json['phone'];
            $status = $json['status'];
            $caseId = $json['caseId'];

            $case = Cases::find($caseId);
            $case->status = $status;
            $case->save();

            $resultCode = 200;
            $message = "Success";
            $data = $case;
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

    public function createCase($citizenId, $longitude, $latitude, $message){
        $case = new Cases();
        $case->citizenId = $citizenId;
        $case->longitude = $longitude;
        $case->latitude = $latitude;
        $case->message = $message;
        $case->status = 0;
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
}
