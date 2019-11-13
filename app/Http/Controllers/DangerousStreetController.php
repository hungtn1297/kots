<?php

namespace App\Http\Controllers;

use App\DangerousStreet;
use Illuminate\Http\Request;

class DangerousStreetController extends Controller
{
    public function getDS(Request $request){
        if($request->is('api/*')){
            $listDSs = DangerousStreet::get();
            $data = [];
            foreach ($listDSs as $ds) {
                $ds['origin'] = ['latitude' => $ds->startLatitude,
                                'longitude' => $ds->startLongitude];
                $ds['destination'] = ['latitude' => $ds->endLatitude,
                                    'longitude' => $ds->endLongitude];
                array_push($data, $ds);
            }
            return response()->json([
                'result' => 200,
                'message' => 'Success',
                'data' => $listDSs
            ]);
        }else{
            $listDSs = DangerousStreet::get();
            // dd($listDSs);
            return view('admin/DangerousStreets/DangerousStreets')->with(compact('listDSs'));
        }
    }

    public function setDS(Request $request){
        $start = explode(',',substr($request->start,1,strlen($request->start)-2));
        $end = explode(',',substr($request->end,1,strlen($request->end)-2));
        $startLatitude = $start[0];
        $startLongitude = $start[1];
        $endLatitude = $end[0];
        $endLongitude = $end[1];

        $ds = new DangerousStreet();
        $ds->startLatitude = $startLatitude;
        $ds->startLongitude = $startLongitude;
        $ds->endLatitude = $endLatitude;
        $ds->endLongitude = $endLongitude;
        $ds->description = 'ABC';

        $ds->save();

        return redirect()->action('DangerousStreetController@getDS');
    }

    public function unsetDS(Request $request){
        $ds = DangerousStreet::find($request->id);
        
        $ds->delete();

        return redirect()->action('DangerousStreetController@getDS');
    }
}
