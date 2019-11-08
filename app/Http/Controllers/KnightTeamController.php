<?php

namespace App\Http\Controllers;

use App\KnightTeam;
use App\Users;
use Illuminate\Http\Request;


class KnightTeamController extends Controller
{
    public function getTeamInfoByKnightId(){
        $resultCode = 3000;
        $message = "";
        $data = array();
    
        $json = json_decode(file_get_contents('php://input'), true);
        if(json_last_error()==0){
            $id = str_replace('+84','0',$json['phone']);
            $knight = Users::find($id);

            $teamId = $knight->team_id;
            $team = KnightTeam::find($teamId);
            
            $team['member'] = $team->knight;
            
            $resultCode = 200;
            $message = "Success";
            $data = $team;
        }else{
            $message = json_last_error_msg();
        }
        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);   
    }

    public function getTeam(Request $request){
        $teams = KnightTeam::with('knight')->get();
        foreach ($teams as $team) {
            $leader = Users::find($team->leaderId);
            $team['leaderName'] = $leader->name;
        }
        // $teams['member'] = $teams->knight();
        if($request->is('api/*')){
            return response()->json([
                'result' => 200,
                'message' => 'ok',
                'data' => $teams
            ]);
        }
    }

    public function createTeam(){
        $team = new KnightTeam();
        $team->name = 'Siêu nhân cuồng phong';
        $team->leaderId = '0971930499';
        $team->save();


    }
}
