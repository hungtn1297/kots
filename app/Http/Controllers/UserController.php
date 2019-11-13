<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class UserController extends Controller
{
    private $WAIT = 0;
    private $ACTIVE = 1;
    private $CITIZEN_ROLE = 1;
    private $KNIGHT_ROLE = 2;
    private $ADMIN_ROLE = 3;

    public function createProfile(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);
            // dd($json);
            $id = str_replace("+84","0",$json['phone']);
            $user = Users::find($id);
            if(!isset($user)){
                $user = new Users();
                $user->id = $id;
                $user->token = $json['token'];
                $user->isFirstLogin = 1;
                // $user->name = $json['name'];
                // $user->address = $json['address']; 
                // $user->gender = $json['gender'];
                $role = $json['role'];
                if($role == $this->CITIZEN_ROLE){
                    $user->role = 1;
                    $user->status = $this->ACTIVE;
                }elseif($role == $this->KNIGHT_ROLE){
                    $user->role = 2;
                    $user->status = $this->WAIT;
                    // $user->team_id = $json['teamId'];
                }
                $user->save();
                $resultCode = 200;
                $message = "Success";
                $data = $user;
            }else{
                $message = "User Exist";
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

    public function updateProfile(){
        $resultCode = 3000;
        $message = "";
        $data = array();

        $json = json_decode(file_get_contents('php://input'), true);
        $id = str_replace("+84","0",$json['phone']);
        $user = Users::find($id);
        if(isset($user)){
            if(isset($json['token'])){
                $user->token = $json['token'];
            }
            if(isset($json['teamId'])){
                $user->team_id = $json['teamId'];
            }else{
                $user->name = $json['name'];
                $user->address = $json['address'];
                $user->gender = $json['gender'];
                $dob = explode('-',$json['dateOfBirth']);
                $user->dateOfBirth = date('Y-m-d',strtotime("$dob[2]-$dob[1]-$dob[0]"));
            }
            $user->isFirstLogin = 0;
            $user->save();
            $user['id'] = $json['phone'];
            $resultCode = 200;
            $message = "Success";
            $data = $user;
        }else{
            $message = "Not Found User";
        }
        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);

    }

    public function findUser(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);
            if(isset($json)){
                $id = str_replace('+84','0',$json['phone']);             
                if(isset($json['role'])){
                    $role = $json['role'];
                    $user = Users::where("id",$id)
                            ->where("role",$role)->first();
                }else{
                    $user = Users::find($id);
                }
                if(isset($user)){
                    $resultCode = 200;
                    $message = "user exist";
                    $user->id = $json['phone'];
                    // dd($user);
                    $data = $user;
                }else{
                    $message = "Not found user";
                }
            }else{
                $resultCode = 3000;
                $message = "Đã xảy ra lỗi";
            }
        }catch(Exception $e){
            $resultCode = 3000;
            $message = $e->getMessage();
        }
        finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }
    }

    public function removeToken(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        $json = $json = json_decode(file_get_contents('php://input'), true);

        $id = str_replace('+84','0',$json['phone']);
        $user = Users::find($id);

        if(isset($user)){
            $user->token = '';
            $user->save();
            $data = $user;
        }else{
            $message = "Not found user";
        }

        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);
    }


}
