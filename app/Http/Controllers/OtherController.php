<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class OtherController extends Controller
{
    public function checkLogin(Request $request){
        // Function dùng để kiểm tra login 

        $json = json_decode(file_get_contents('php://input'), true);
        if(isset($json)){
            $resultCode = 3000;
            $message = '';
            $data = [];
            $id = str_replace('+84','0',$json['phone']);
            $user = Users::find($id);
            if($user->count()>0){
                $user->token = $json['token'];
                $user->save();
                $resultCode = 200;
            }else{
                $user = [];
                $message = 'error';
            }
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $user
            ]);
        }else{
            $phone = $request->phone;
            $password = $request->password;
            $user = Users::where('id', $phone)
                        ->where('password', $password)
                        ->where('role', 3)->get();
            if($user->count()>0){
                return redirect()->action('CitizenController@get');
            }else{
                $error = "Wrong phone or password";
                return view('login')->with(compact('error'));
            }
        } 
    }

    public function getDistance($longitudeFrom, $latitudeFrom, $longitudeTo, $latitudeTo, $unit = ''){
        // Google API key
        $apiKey = 'AIzaSyDRJl0JFqHhM8jQ24VrJnzJE8HarKJ1qF0';
        
        // // Change address format
        // $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        // $formattedAddrTo     = str_replace(' ', '+', $addressTo);
        
        // // Geocoding API request with start address
        // $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
        // $outputFrom = json_decode($geocodeFrom);
        // if(!empty($outputFrom->error_message)){
        //     return $outputFrom->error_message;
        // }
        
        // // Geocoding API request with end address
        // $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
        // $outputTo = json_decode($geocodeTo);
        // if(!empty($outputTo->error_message)){
        //     return $outputTo->error_message;
        // }
        
        // // Get latitude and longitude from the geodata
        // $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
        // $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
        // $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
        // $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
        
        // Calculate distance between latitude and longitude
        $theta    = $longitudeFrom - $longitudeTo;
        $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist    = acos($dist);
        $dist    = rad2deg($dist);
        $miles    = $dist * 60 * 1.1515;
        // dd($miles);
        
        // Convert unit and return distance
        $unit = strtoupper($unit);
        if($unit == "K"){
            return round($miles * 1.609344, 2).' km';
        }elseif($unit == "M"){
            return round($miles * 1609.344, 2).' meters';
        }else{
            return round($miles, 2).' miles';
        }
    }

    public function getDistanceRadians($lat1, $lon1, $lat2, $lon2){
        # $lat1 and $lon1 are the coordinates of the first point in radians
        # $lat2 and $lon2 are the coordinates of the second point in radians
        $a = sin(($lat2 - $lat1)/2.0);
        $b = sin(($lon2 - $lon1)/2.0);
        $h = ($a*$a) + cos($lat1) * cos($lat2) * ($b*$b);
        $theta = 2 * asin(sqrt($h)); # distance in radians
        return $theta;
        # in order to find the distance, multiply $theta by the radius of the earth, e.g.
        # $theta * 6,372.7976 = distance in kilometres (value from http://en.wikipedia.org/wiki/Earth_radius)
        }
    }
