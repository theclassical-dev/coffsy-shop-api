<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Models\DailyReport;
use App\Models\WeeklyReport;
use App\Models\MonthlyReport;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function report(Request $request){
        
        $request->validate([
            'delivered' => 'required',
            'cash' => 'required',
            'trasfer' => 'required',
        ]);

        // cash + transfer = total
        $total = $request->input('cash') + $request->input('transfer');

        $report = DailyReport::create([
            'delivered' => $request->input('delivered'),
            'cash' => $request->input('cash'),
            'trasfer' => $request->input('trasfer'),
            'total' => $total,
            'submittedDate' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
        ]);

        if($report){
            return response()->json(['message' =>'Report Successfully Created']);
        }
        return response()->json(['message' =>'Error']);

    }

    public function WeeklyReport(Request $request){
        
        $request->validate([
            'delivered' => 'required',
            'cash' => 'required',
            'trasfer' => 'required',
        ]);

        // sum of all the daily reports
        $d = DailyReport::sum('delivered');
        $cash = DailyReport::sum('cash');
        $transfer = DailyReport::sum('transfer');
        $total = DailyReport::sum('total');


        $report = WeeklyReport::create([
            'delivered' => $d,
            'cash' => $cash,
            'trasfer' => $transfer,
            'total' => $total,
            'submittedDate' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
        ]);

        if($report){
            return response()->json(['message' =>'Weekly Report Successfully Submitted']);
        }
        return response()->json(['message' =>'Error']);
    }
}
