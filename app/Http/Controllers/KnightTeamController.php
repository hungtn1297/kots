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
            $team = KnightTeam::find($teamId)->with('knight')->first();

            $leader = Users::find($team->leaderId);
            $team['leaderName'] = $leader->name;
            // $team['member'] = $team->knight;
            
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
        if($request->is('api/*')){
            $json = json_decode(file_get_contents('php://input'), true);
            if(isset($json['teamId'])){
                $teams = KnightTeam::with('knight')->find($json['teamId']);
                $leader = Users::find($teams->leaderId);
                $teams['leaderName'] = $leader->name;
                $teams['leaderId'] = '+84'.substr($teams['leaderId'],1,strlen($teams['leaderId']));
                foreach ($teams->knight as $knight) {
                    $knight['id'] = '+84'.substr($knight['id'],1,strlen($knight['id']));
                }
            }else{
                $teams = KnightTeam::with('knight')->get();
                foreach ($teams as $team) {
                    $leader = Users::find($team->leaderId);
                    $team['leaderName'] = $leader->name;
                    $team['leaderId'] = '+84'.substr($team['leaderId'],1,strlen($team['leaderId']));
                    foreach ($team->knight as $knight) {
                        $knight['id'] = '+84'.substr($knight['id'],1,strlen($knight['id']));
                    }
                }
            }
            // $teams['member'] = $teams->knight();
            
            return response()->json([
                'result' => 200,
                'message' => 'SUCCESS',
                'data' => $teams
            ]);
        }else{
            $teams = KnightTeam::with('knight')->get();
            foreach ($teams as $team) {
                $leader = Users::find($team->leaderId);
                $team['leaderName'] = $leader->name;
            }
            // dd($teams);
            return view('admin/KnightTeam/ListKnightTeam')->with(compact('teams'));
        }
    }

    public function createTeam(){
        $team = new KnightTeam();
        $team->name = 'Siêu nhân cuồng phong';
        $team->leaderId = '0971930499';
        $team->save();


    }

    public function getWaitingKnight(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        $listWaitingKnight = Users::where('team_id', $json['teamId'])
                                    ->where('status', 0)
                                    ->get();
        
        if(!empty($listWaitingKnight)){
            $resultCode = 200;
            $message = 'SUCCESS';
            $data = $listWaitingKnight;
        }

        return response()->json([
            'result' => 200,
            'message' => 'SUCCESS',
            'data' => $data
        ]);
    }
}
