<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class CitizenController extends Controller
{
    public function get(Request $request){
        // Function dùng để lấy danh sách citizen
        $listCitizens = Users::where('role',1)->get();
        return view('admin/Citizen/ListCitizen')->with(compact('listCitizens'));
    }

    public function viewProfile(Request $request){
        // Function dùng để xem thông tin chi tiết của citizen
        $citizen = Users::where('role',1)
                        ->where('id', $request->id)
                        ->get();
        if($citizen->count()>0){
            $citizen = $citizen[0];
            
            return view('admin/Citizen/ProfileCitizen')->with(compact('citizen'));
        }else{
            $error = "Không tìm thấy thông tin chi tiết của người dân này";
            return view('admin/error')->with(compact('error'));
        }
    }

    public function disable(Request $request){
        // Function dùng để điều chỉnh trạng thái của Citizen
        // Nếu trạng thái là disable thì sẽ trở thành available
        // Nếu trạng thái là availabel sẽ trở thành disable
        $citizen = Users::where('role',1)
                        ->where('id', $request->id)
                        ->get();
        if($citizen->count()>0){
            $citizen = $citizen[0];
            if($citizen->isDisable == 0){
                $citizen->isDisable = 1;
            }else{
                $citizen->isDisable = 0;
            }
            $citizen->save();
            return redirect()->action('CitizenController@get');
        }else{
            $error = "Không tìm thấy thông tin chi tiết của người dân này";
            return view('admin/error')->with(compact('error'));
        }
    }
}
