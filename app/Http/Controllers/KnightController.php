<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class KnightController extends Controller
{
    public function get(){
        // Function dùng để lấy danh sách các hiệp sĩ
        $listKnights = Users::where('role',2)->get();
        return view('admin/Knight/ListKnight')->with(compact('listKnights'));
    }

    public function viewProfile(Request $request){
        // Function dùng để xem thông tin chi tiết của hiệp sĩ
        $knight = Users::where('role',2)
                        ->where('id', $request->id)
                        ->get();
        // dd($knight);
        if($knight->count()>0){
            $knight = $knight[0];
            return view('admin/Knight/ProfileKnight')->with(compact('knight'));
        }else{
            $error = "Không tìm thấy thông tin chi tiết của hiệp sĩ này";
            return view('admin/error')->with(compact('error'));
        }
    }

    public function disable(Request $request){
        // Function dùng để điều chỉnh trạng thái của Citizen
        // Nếu trạng thái là disable thì sẽ trở thành available
        // Nếu trạng thái là availabel sẽ trở thành disable
        $knight = Users::where('role',2)
                        ->where('id', $request->id)
                        ->get();
        if($knight->count()>0){
            $knight = $knight[0];
            if($knight->isDisable == 0){
                $knight->isDisable = 1;
            }else{
                $knight->isDisable = 0;
            }
            $knight->save();
            return redirect()->action('KnightController@get');
        }else{
            $error = "Không tìm thấy thông tin chi tiết của hiệp sĩ này";
            return view('admin/error')->with(compact('error'));
        }
    }
}
