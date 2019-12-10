<?php

namespace App\Http\Controllers;

use App\Criminal;
use Illuminate\Http\Request;

class CriminalController extends Controller
{
    public function getCriminal(){
        $listCriminals = Criminal::get();
        return view('admin/criminal/ListCriminal')->with(compact('listCriminals'));
    }

    public function get(){
        $listCriminals = Criminal::where('status', 1)->get();

        return $this->returnAPI(200,'SUCCESS',$listCriminals);
    }
}
