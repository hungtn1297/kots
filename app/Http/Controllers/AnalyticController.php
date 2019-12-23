<?php

namespace App\Http\Controllers;

use App\Cases;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function get(Request $request){
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

            
            $tmpDisArr = [];
            $districtArr = ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5',
                            'Quận 6', 'Quận 7', 'Quận 8', 'Quận 9', 'Quận 10',
                            'Quận 11', 'Quận 12', 'Thủ Đức', 'Gò Vấp', 'Bình Thạnh',
                            'Tân Bình', 'Tân Phú', 'Phú Nhuận', 'Bình Tân', 'Củ Chi',
                            'Hóc Môn', 'Bình Chánh', 'Nhà Bè', 'Cần Giờ'];
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            
            $barDataAllDistrict = [];
            foreach ($districtArr as $district) {
                if(isset($startDate) && isset($endDate)){
                    $case = Cases::where('district', $district)
                            ->where('created_at','>',$startDate)
                            ->where('created_at','<',$endDate)
                            ->get();
                }else{
                    $case = Cases::where('district', $district)
                            ->where('created_at','>',Carbon::now()->subWeek(1))
                            ->get();
                }
                $tmpDisArr['district'] = $district;
                $tmpDisArr['all'] = count($case);
                $tmpDisArr['success'] = count($case->where('status',2));
                $tmpDisArr['fail'] = count($case->where('status',3));

                array_push($barDataAllDistrict,(object) $tmpDisArr);
            }
            $all = array_column($barDataAllDistrict,'all');
            array_multisort($all, SORT_DESC, $barDataAllDistrict);
            $barData = array_slice($barDataAllDistrict,0,5,true);
        }
        if(isset($startDate) && isset($endDate)){
            return view('admin/Analytics/Analytics')->with(compact('totalCase', 'successCase', 'failCase','barData','startDate','endDate'));
        }
        return view('admin/Analytics/Analytics')->with(compact('totalCase', 'successCase', 'failCase','barData'));
    }

}
