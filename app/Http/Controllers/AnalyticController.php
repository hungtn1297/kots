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
        $totalCase = [];
        $successCase = [];
        $failCase = [];
        for ($i=1; $i <= 12 ; $i++) { 
            $caseinMonth = $case = Cases::where('created_at','>',Carbon::now()->subMonth(12))
                                    ->whereMonth('created_at',$i)
                                    ->get();
            $successCaseInMonth = $case = Cases::where('created_at','>',Carbon::now()->subMonth(12))
                                    ->whereMonth('created_at',$i)
                                    ->where('status',2)
                                    ->get();
            $failCaseInMonth = $case = Cases::where('created_at','>',Carbon::now()->subMonth(12))
                                    ->whereMonth('created_at',$i)
                                    ->where('status',2)
                                    ->get();
            $totalCase[$i] = count($caseinMonth);
            $successCase[$i] = count($successCaseInMonth);
            $failCase[$i] = count($failCaseInMonth);
        }
        // dd($successCase);
        // dd($data);
        return view('admin/Analytics/Analytics')->with(compact('totalCase', 'successCase', 'failCase'));
    }
}
