<?php

namespace App\Http\Controllers;

use App\PoliceContact;
use Illuminate\Http\Request;

class PoliceContactController extends Controller
{
    public function get(Request $request){
        $resultCode = 3000;
        $message = "";
        $data = array();
        
        
        if($request->is('api/*')){
            $policeContact = PoliceContact::get();

            $resultCode = 200;
            $message = "Success";
            $data = $policeContact;

            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }else{
            $listPoliceContacts = PoliceContact::get();
            // dd($listPoliceContacts);
            return view('admin/PoliceContact/ListPoliceContact')->with(compact('listPoliceContacts'));
        }
    }

    public function create(Request $request){
        $name = $request->name;
        $phone = $request->phone;
        $address = $request->address;
        $id = $request->id;

        if(isset($id)){ //Update
            $policeContact = PoliceContact::find($id);
        }else{          //Insert
            $policeContact = new PoliceContact();
        }

        $policeContact->name = $name;
        $policeContact->phone = $phone;
        $policeContact->address = $address;

        $policeContact->save();
        return redirect()->action('PoliceContactController@get');
    }

    public function delete(Request $request){
        $id = $request->id;
        if(isset($id)){
            $policeContact = PoliceContact::find($id);
            $policeContact->delete();
            return redirect()->action('PoliceContactController@get');
        }else{
            $error = "Không tìm thấy thông tin";
            return view('admin/error')->with(compact('error'));
        }
    }
}
