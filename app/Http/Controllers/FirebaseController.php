<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\OtherController;

class FirebaseController extends Controller
{
    public function getFirebaseDB(){
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->create();

        $database = $firebase->getDatabase();
        return $database;
    }

    public function getKnightInRadius($radius = 5, $longitude, $latitude){

        $controller = new OtherController();
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->create();

        $database = $firebase->getDatabase();
        $ref = $database->getReference('teamID');
        $teamId = $ref->getSnapshot()->getValue();

        $result = [];
        $knightLocation = [];
        foreach ($teamId as $team) {
            if(isset($team)){
                foreach ($team as $member) {
                    // dd($member);
                    $knightId = end($member)['user']['id'];
                    $long = end($member)['longitude'];
                    $lat = end($member)['latitude'];
                    $knight['id'] = $knightId;
                    $knight['long'] =$long;
                    $knight['la'] = $lat;
                    // dd($knight);
                    array_push($knightLocation,$knight);
                }
            }
        }
        // dd($knightLocation);
        foreach ($knightLocation as $knight) {
            $knightDistance = $controller->getDistance($knight['la'],$knight['long'],$latitude,$longitude,'K');
            if($knightDistance < $radius){
                $knight['distance'] = $knightDistance;
                array_push($result, $knight);
            }
            // echo $controller->getDistance(10.838387, 106.670402,10.836658, 106.664859,'K')."<br>";
        }
        return $result;
    }

    public function getKnightLocation($knightId, $teamId){
        $firebaseDB = $this->getFirebaseDB();
        $idVN = '+84'.substr($knightId, 1, strlen($knightId));
        // dd($knightId.' - '.$teamId);
        $knight = $firebaseDB->getReference('teamID/'.$teamId)
                            ->getChild($idVN)
                            ->getValue();
        // dd($knight);

        $location =  [
                    'latitude' => end($knight)['latitude'],
                    'longitude' => end($knight)['longitude']
                    ];
        return $location;
    }

    public function createFirebaseCase($caseID, $knightId, $data=''){
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->create();
        // dd($knightId);
        $idVN = '+84'.substr($knightId, 1, strlen($knightId));
        $database = $firebase->getDatabase();
        if(empty($knightId)){
            $newCase = $database->getReference('caseID')
                            ->getChild($caseID)
                            ->set($data);
        }else{
            $newCase = $database->getReference('caseID')
                                ->getChild($caseID)
                                ->set($idVN);

            $newCase = $database->getReference('caseID/'.$caseID)
                                ->getChild($idVN)
                                ->push($data);
        }
        

        return $newCase;
    }

    public function getKnightInTeamLocation($knightId, $teamId, $startTime, $endTime = ''){
        $firebaseDB = $this->getFirebaseDB();
        $idVN = '+84'.substr($knightId, 1, strlen($knightId));
        $knightLocationList = $firebaseDB->getReference('teamID/'.$teamId)
                                ->getChild($idVN)
                                ->getValue();
        $location = array();

        // dd($knightLocationList);
        foreach ($knightLocationList as $knightLocation) {
            //Nếu starttime trùng với createdAt trên firebase
            if(Carbon::parse($knightLocation['createdAt']) >= $startTime){
                //Nếu chưa đến endTime
                // echo("$knightLocationTime"."</br>");
                if(Carbon::parse($knightLocation['createdAt']) < $endTime){
                    array_push($location, [
                        'latitude' => $knightLocation['latitude'],
                        'longitude' => $knightLocation['longitude']
                        ]);
                //Nếu đã khớp endTime
                }else{
                    // dd($knightLocation['latitude'].' - '.$knightLocation['longitude']);
                    //Insert giá trị cuối ở end time
                    array_push($location, [
                        'latitude' => $knightLocation['latitude'],
                        'longitude' => $knightLocation['longitude']
                        ]);
                    break;
                }
                
            }
        }
        return $location;
    }

    public function welcome(){
        $location = $this->getKnightInTeamLocation('0971930499','1','2019-11-08 06:09:45','2019-11-08 11:40:46');
        echo '<pre>'; print_r($location); echo '</pre>';
    }

}
