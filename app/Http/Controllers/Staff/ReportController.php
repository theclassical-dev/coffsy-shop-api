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
    //create daily report
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
            return response()->json(['message' =>'Report Successfully Submmitted']);
        }
        return response()->json(['message' =>'Error']);

    }

    //update daily report
    public function updateReport(Request $request, $id){
        $report = DailyReport::find($id);
        if($report){
            $report->update($request->all());
            return response()->json([
                'message' => 'Report Successfully Edited',
                'data' => $report
            ]);
        }
    }

    //create weeklyReport
    public function weeklyReport(Request $request){

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
            DailyReport::truncate();
            return response()->json(['message' =>'Weekly Report Successfully Submitted']);
        }
        return response()->json(['message' =>'Error']);
    }

    //create month report
    public function monthlyReport(Request $request){
        
        $request->validate([
            'month' => 'required|unique:monthly_reports, month',
        ]);

        // sum of all the daily reports
        $d = WeeklyReport::sum('delivered');
        $cash = WeeklyReport::sum('cash');
        $transfer = WeeklyReport::sum('transfer');
        $total = WeeklyReport::sum('total');


        $report = MonthlyReport::create([
            'delivered' => $d,
            'cash' => $cash,
            'trasfer' => $transfer,
            'total' => $total,
            'month' => $request->input('month'),
            'submittedDate' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
        ]);

        if($report){

            WeeklyReport::truncate();
            return response()->json(['message' =>'Monthly Report Successfully Submitted']);
        }
            return response()->json(['message' =>'Error']);
    }

    //create yearly report
    public function yearlyReport(Request $request){
        
        $request->validate([
            'year' => 'required|unique:yearly_reports, month',
        ]);

        // sum of all the daily reports
        $d = MonthlyReport::sum('delivered');
        $cash = MonthlyReport::sum('cash');
        $transfer = MonthlyReport::sum('transfer');
        $total = MonthlyReport::sum('total');


        $report = YearlyReport::create([
            'delivered' => $d,
            'cash' => $cash,
            'trasfer' => $transfer,
            'total' => $total,
            'year' => $request->input('year'),
            'submittedDate' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
        ]);

        if($report){

            MonthlyReport::truncate();
            return response()->json(['message' =>'Yearly Report Successfully Submitted']);
        }
            return response()->json(['message' =>'Error']);
    }
}
