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

    public function createProfile(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);
            $id = $json['phone'];
            $user = Users::find($id);
            if(!isset($user)){
                $user = new Users();
                $user->id = str_replace("+84","0",$id);
                $user->name = $json['name'];
                $user->address = $json['address'];
                $role = $json['role'];
                if($role == $this->CITIZEN_ROLE){
                    $user->role = 1;
                    $user->status = $this->ACTIVE;
                }elseif($role == $this->KNIGHT_ROLE){
                    $user->role = 2;
                    $user->status = $this->WAIT;
                    $user->team_id = $json['teamId'];
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
        }
        finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }
    }
}
