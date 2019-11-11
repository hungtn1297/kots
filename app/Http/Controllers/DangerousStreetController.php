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
                                'longitude' => $ds->startLongtitude];
                $ds['destination'] = ['latitude' => $ds->endLatitude,
                                    'longitude' => $ds->endLongtitude];
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
}
