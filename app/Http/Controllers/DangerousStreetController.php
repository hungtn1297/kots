<?php

namespace App\Http\Controllers;

use App\Cases;
use App\DangerousStreet;
use App\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DangerousStreetController extends Controller
{
    public function getDS(Request $request){
        if($request->is('api/*')){
            $listDSs = DangerousStreet::where('expiredDate','>',Carbon::now())->get();
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

    public function setDSByLatLng($latStr, $latEnd, $longStr, $longEnd, $des){
        $dangerousStreet = new DangerousStreet();
        $dangerousStreet->startLongitude = $longStr;
        $dangerousStreet->endLongitude = $longEnd;
        $dangerousStreet->startLatitude = $latStr;
        $dangerousStreet->endLatitude = $latEnd;
        $dangerousStreet->description = $des;
        $dangerousStreet->save();
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

    public function checkDS(){
        $casesInMonth = Cases::where(
            'created_at', '>=', Carbon::now()->subMonth()->toDateTimeString()
        )->whereIn('status',[2,3])->get();
        // dd($casesInMonth);
        $dsStreet = [];
        foreach ($casesInMonth as $case) {
            // $address = explode(',',$case->address)[0];
            $address = $case->address;
            $address = str_replace('/',' ',$address);
            $address = trim(preg_replace('/\d{1,} /', '', $address));
            // $address = trim(preg_replace('/\//', '', $address));
            $latLng = [
                'lat' => $case->startLatitude,
                'long' => $case->startLongitude
            ];
            $latLongArr = [];
            if (!array_key_exists($address, $dsStreet)) {
                array_push($latLongArr, $latLng);
                $dsStreet[$address] = $latLongArr;
            }else{
                $latLongArr = $dsStreet[$address];
                array_push($latLongArr, $latLng);
                $dsStreet[$address] = $latLongArr;
                if(count($dsStreet[$address]) >= 3){
                    $latLng = $this->getLatLongStartEnd($dsStreet[$address]);
                    // dd($latLng);
                    if(!empty($latLng)){
                        $ds = DangerousStreet::where('description',$address)->first();
                        if(count($ds) > 0){
                            $ds->expiredDate = Carbon::now()->addDay(7);
                            $ds->startLongitude = $latLng[2];
                            $ds->startLatitude = $latLng[0];
                            $ds->endLongitude = $latLng[3];
                            $ds->endLatitude = $latLng[1];
                            $ds->save();
                        }else{
                            $this->setDSByLatLng($latLng[0], $latLng[1], $latLng[2], $latLng[3], $address);
                        }
                    }
                }
            }
            
        }
        // dd($dsStreet);
    }

    public function getLatLongStartEnd($latLngArr){
        $max = -1;
        $otherController = new OtherController();
        $returnArr = [];
        for ($i=0; $i < count($latLngArr)-1; $i++) { 
            if($otherController->getDistance($latLngArr[$i]['lat'], $latLngArr[$i+1]['lat']) > $max){
                $max = $otherController->getDistance($latLngArr[$i]['lat'], $latLngArr[$i+1]['lat']);
                // $latStr, $latEnd, $longStr, $longEnd, $des
                array_push($returnArr, $latLngArr[$i]['lat']);
                array_push($returnArr, $latLngArr[$i+1]['lat']);
                array_push($returnArr, $latLngArr[$i]['long']);
                array_push($returnArr, $latLngArr[$i+1]['long']);
            }
        }
        return $returnArr;
    }
}
