<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Http\Controllers\CaseController;

class MessageController extends Controller
{
    public function receiveMessage(Request $request){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $jsonData = json_decode(file_get_contents('php://input'),true);
            if(isset($jsonData)){
                $id = $jsonData['phone'];
                $userMessage = $jsonData['message'];
                $longitude = $jsonData['longitude'];
                $latitude = $jsonData['latitude'];
                $type = $jsonData['type'];
                
                $user = Users::find($id);
                $caseController = new CaseController();
                $userName = $user->name;
                $userPhone = $user->id;
                $case = $caseController->createCase($id, $longitude, $latitude, $userMessage, $type);
                $resultCode = 200;
                $data = $case;
            }
        }catch(Exception $e){
            dd($e->getMessage());
            $resultCode = 404;
            $message = $e->getMessage();
        }
        finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data,
            ]);
        }
        
    }
}
