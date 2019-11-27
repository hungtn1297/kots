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

    public function getDistance($latitudeFrom, $latitudeTo){
        return abs(($latitudeFrom-$latitudeTo)*111);
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
