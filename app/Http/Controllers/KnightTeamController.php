<?php

namespace App\Http\Controllers;

use DB;
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
                $teams = KnightTeam::with('knight')
                                    ->find($json['teamId']);
                $leader = Users::where('team_id', $teams->id)
                                    ->where('isLeader',1)
                                    ->where('status',0)
                                    ->where('isDisable',1)
                                    ->first();
                if(isset($leader)){
                    $teams['leaderName'] = $leader->name;
                    $teams['leaderId'] = '+84'.substr($leader->id,1,strlen($leader->id));
                }
                foreach ($teams->knight as $knight) {
                    $knight->id = '+84'.substr($knight->id,1,strlen($knight->id));
                }
            }else{
                $teams = KnightTeam::with('knight')
                                    ->where('status',1)
                                    ->get();
                foreach ($teams as $team) {
                    $leader = Users::where('team_id', $team->id)
                                    ->where('isLeader',1)
                                    ->where('status',0)
                                    ->where('isDisable',1)
                                    ->first();
                    if(isset($leader)){
                        $team['leaderName'] = $leader->name;
                        $team['leaderId'] = '+84'.substr($leader->id,1,strlen($leader->id));
                    }
                    foreach ($team->knight as $knight) {
                        $knight->id = '+84'.substr($knight->id,1,strlen($knight->id));
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
            $teams = KnightTeam::with('adminGetKnight')->get();
            // dd($teams);
            $flag = 0;
            foreach ($teams as $team) {
                foreach ($team->adminGetKnight as $knight) {
                    if($knight->isLeader == 1){
                        $leader = $knight;
                        $team['leaderName'] = $leader->name;
                        $flag = 1;
                    }
                }
            }
            // dd($teams);
            // $countTeam = KnightTeam::where('status',0)->get();
            return view('admin/KnightTeam/ListKnightTeam')->with(compact('teams'));
        }
    }

    public function createTeam(Request $request){
        $resultCode = 3000;
        $message = "";
        $data = array();
    
        $json = json_decode(file_get_contents('php://input'), true);

        $id = str_replace('+84','0',$json['phone']);
        DB::beginTransaction();
        $team = new KnightTeam();
        $team->name = $json['name'];
        $team->address = $json['address'];
        $team->status = 0;
        // 
        // $team->status = 1;
        //
        $insertTeam = $team->save();

        $user = Users::find($id);
        $user->isLeader = 1;
        $user->team_id = $team->id;

        //
        // $user->status = 1;
        //

        $insertUser = $user->save();

        if($insertTeam == true && $insertUser == true){
            DB::commit();
            $resultCode = 200;
            $message = 'SUCCESS';
            $data = $team;
        }else{
            DB::rollback();
        }

        return $this->returnAPI($resultCode,$message,$data);
    }

    public function getWaitingKnight(){
        $resultCode = 3000;
        $message = 'FAIL';
        $data = [];

        $json = json_decode(file_get_contents('php://input'), true);
        $listWaitingKnight = Users::where('team_id', $json['teamId'])
                                    ->where('status', 0)
                                    ->get();
        
        $listLeaveKnight = Users::where('team_id', $json['teamId'])
                                    ->where('status', 3)
                                    ->get();
        if(!empty($listWaitingKnight)){
            $resultCode = 200;
            $message = 'SUCCESS';
            foreach ($listWaitingKnight as $knight) {
                $knight->id = '+84'.substr($knight->id,1,strlen($knight->id));
            }
            foreach ($listLeaveKnight as $knight) {
                $knight->id = '+84'.substr($knight->id,1,strlen($knight->id));
            }

            $data['listJoin'] = $listWaitingKnight;
            $data['listLeave'] = $listLeaveKnight;
        }

        return response()->json([
            'result' => 200,
            'message' => 'SUCCESS',
            'data' => $data
        ]);
    }

    public function changeTeamStatus(Request $request){
        $flag = true;
        $teamId = $request->id;
        $status = $request->status;
        // dd($teamId);
        DB::beginTransaction();
        $team = KnightTeam::find($teamId);
        $team->status = $status;
        $flag =  $flag && $team->save();
        // dd($flag);
        
        if($status == 1){
            $leaderInTeam = Users::where('team_id', $teamId)
                            ->where('isLeader', 1)->first();
            $leaderInTeam->status = 1;
            $flag = $flag && $leaderInTeam->save();
            // dd($flag);
        }

        if($status == -1){
            $teamMembers = Users::where('team_id', $teamId)->get();
            foreach ($teamMembers as $member) {
                $member->isDisable = 1;
                $flag =  $flag && $member->save();
            }
        }
        
        if($flag == true){
            DB::commit();
            return redirect()->action('KnightTeamController@getTeam');
        }else{
            DB::rollback();
            $error = 'Đã xảy ra lỗi';
            return view('error')->with(compact('error'));
        }
    }

    public function getTeamDetail(Request $request){
        $teamId = $request->id;
        // dd($teamId);
        $knightTeam = KnightTeam::find($teamId);

        $teamMembers = Users::where('team_id', $teamId)
                            ->where('role', 2)
                            ->get();

        // dd($knightTeam);

        return view('admin/KnightTeam/DetailKnightTeam')->with(compact('knightTeam','teamMembers'));
    }
}
