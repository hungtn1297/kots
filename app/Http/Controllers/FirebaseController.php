<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\OtherController;

class FirebaseController extends Controller
{
    public function index($radius = 5){

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
            $knightDistance = $controller->getDistance($knight['la'],$knight['long'],10.838312,106.672020,'K');
            if($knightDistance < $radius){
                $knight['distance'] = $knightDistance;
                array_push($result, $knight);
            }
            // echo $controller->getDistance(10.838387, 106.670402,10.836658, 106.664859,'K')."<br>";
        }
        return $result;
    }
}
