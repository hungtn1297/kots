<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseController extends Controller
{
    public function index(){

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
                    ->withServiceAccount($serviceAccount)
                    ->create();

        $database = $firebase->getDatabase();
        $ref = $database->getReference('knights');

        dd($ref->getSnapshot()->getValue());
    }
}
