<?php

namespace App\Http\Controllers;

use App\KnightTeam;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $countTeam = count(KnightTeam::where('status',0)->get());
        // dd($countTeam);
        view()->share('countTeam', $countTeam);
    }

    public function returnAPI($resultCode, $message, $data){
        return response()->json([
            'result' => $resultCode,
            'message' => $message,
            'data' => $data
        ]);
    }
}
