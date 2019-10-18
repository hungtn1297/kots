<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class OtherController extends Controller
{
    public function checkLogin(Request $request){
        // Function dùng để kiểm tra login 

        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data)){
            $phone = $data['phone'];
            $user = Users::where('phone', $phone)->get();
            if($user->count()>0){
                return response()->json([
                    'user' => $user
                ]);
            }else{
                return response()->json([
                    'result' => 'fail'
                ]);
            }
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
}
