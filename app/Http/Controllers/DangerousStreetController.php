<?php

namespace App\Http\Controllers;

use App\DangerousStreet;
use App\Users;
use Illuminate\Http\Request;

class DangerousStreetController extends Controller
{
    public function getDS(Request $request){
        if($request->is('api/*')){
            $listDSs = DangerousStreet::get();
            $data = [];
            foreach ($listDSs as $ds) {
                $ds['origin'] = ['latitude' => $ds->startLatitude,
                                'longitude' => $ds->startLongitude];
                $ds['destination'] = ['latitude' => $ds->endLatitude,
                                    'longitude' => $ds->endLongitude];
                array_push($data, $ds);
            }
            return response()->json([
                'result' => 200,
                'message' => 'Success',
                'data' => $listDSs
            ]);
        }else{
            $listDSs = DangerousStreet::get();
            // dd($listDSs);
            return view('admin/DangerousStreets/DangerousStreets')->with(compact('listDSs'));
        }
    }

    public function setDS(Request $request){
        if(isset($request->start) && isset($request->end)){
            $start = explode(',',substr($request->start,1,strlen($request->start)-2));
            $end = explode(',',substr($request->end,1,strlen($request->end)-2));
            $startLatitude = $start[0];
            $startLongitude = $start[1];
            $endLatitude = $end[0];
            $endLongitude = $end[1];

            $ds = new DangerousStreet();
            $ds->startLatitude = $startLatitude;
            $ds->startLongitude = $startLongitude;
            $ds->endLatitude = $endLatitude;
            $ds->endLongitude = $endLongitude;
            $ds->description = 'ABC';

            $ds->save();

            return redirect()->action('DangerousStreetController@getDS');
        }else{
            $error = "Not Enough Data";
            return view('admin/error')->with(compact('error'));
        }
        
    }

    public function unsetDS(Request $request){
        $ds = DangerousStreet::find($request->id);
        
        $ds->delete();

        return redirect()->action('DangerousStreetController@getDS');
    }

    public function alertDS(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        $id = str_replace('+84','0',$json['phone']);
        $action = $json['action'];

        $citizen = Users::find($id);

        $messageController = new MessageController();
        $result = $messageController->sendAlertToCitizen($citizen->token, $action);
        if($result > 0){
            $resultCode = 200;
            $message = 'SUCCESS';
        }

        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);
    }
}
