<?php

namespace App\Http\Controllers;

use App\Cases;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function get(){
        // dd($case);
        $data = [];
        for ($i=1; $i <= 12 ; $i++) { 
            $caseinMonth = $case = Cases::where('created_at','>',Carbon::now()->subMonth(12))
                                    ->whereMonth('created_at',$i)
                                    ->get();
            $data[$i] = count($caseinMonth);
        }
        // dd($data);
        return view('admin/Analytics/Analytics')->with(compact('data'));
    }
}
