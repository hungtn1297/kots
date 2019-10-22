<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class KnightController extends Controller
{
    public function get(){
        // Function dùng để lấy danh sách các hiệp sĩ
        $listKnights = Users::where('role',2)->get();
        // dd($listKnights);
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

    public function findKnight(){
        $resultCode = 3000;
        $message = "";
        $data = array();
        try{
            $json = json_decode(file_get_contents('php://input'), true);
            if(isset($json)){
                $id = str_replace("+84","0",$json['phone']);
                $knight = Users::find($id);
                if($knight->count()>0){
                    $resultCode = 200;
                    $message = "Knight exist";
                    $data = [
                        'name' => $knight->name,
                        'address' => $knight->address,
                        'status' => $knight->status,
                        'isDisalbe' => $knight->isDisable
                    ];
                }else{
                    $resultCode = 404;
                    $message = "Not found Knight";
                }
            }else{
                $resultCode = 3000;
                $message = "Đã xảy ra lỗi";
            }
        }catch(Exception $e){
            $resultCode = 3000;
            $message = $e->getMessage();
        }finally{
            return response()->json([
                'result' => $resultCode,
                'message' => $message,
                'data' => $data
            ]);
        }
    }
}
