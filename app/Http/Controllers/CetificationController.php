<?php

namespace App\Http\Controllers;

use App\CetificationInformation;
use Illuminate\Http\Request;

class CetificationController extends Controller
{
    public function insert($userId, $image, $description){
        $ceti = new CetificationInformation();
        $ceti->userId = $userId;
        $ceti->image = $image;
        $ceti->description = $description;
        $save = $ceti->save();
        
        return $save;
    }
}
