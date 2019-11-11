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
                $ds['origin'] = $ds->startLatitude.','.$ds->startLongtitude;
                $ds['destination'] = $ds->endLatitude.','.$ds->endLongtitude;
                array_push($data, $ds);
            }
            return response([
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
