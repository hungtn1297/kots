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
                'resultCode' => $resultCode,
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
}
