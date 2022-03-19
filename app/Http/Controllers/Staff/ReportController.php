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
    // get all records daily, weekly & monthly
    public function index(){
        $report = DailyReport::all();

        if(!$report->isEmpty()){

            return response()->json(['data' => $report,]);
        }

        return response()->json(['message' => 'No Report is found']);
        
       
    }
    public function weekly(){
        $report = WeeklyReport::all();
        if(!$report->isEmpty()){

            return response()->json(['data' => $report,]);
        }

        return response()->json(['message' => 'No Report is found']);
        
       
    }
    public function monthly(){
        $report = MonthlyReport::all();
        if($report){

            return response()->json(['data' => $report,]);
        }

        return response()->json(['message' => 'No Report is found']);
        
       
    }

    //create daily report
    public function report(Request $request){
        
        $request->validate([
            'delivered' => 'required',
            'cash' => 'required',
            'transfer' => 'required',
        ]);

        // cash + transfer = total
        $total = $request->input('cash') + $request->input('transfer');

        $report = DailyReport::create([
            'delivered' => $request->input('delivered'),
            'cash' => $request->input('cash'),
            'transfer' => $request->input('transfer'),
            'total' => $total,
            'submittedDate' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
        ]);

        if($report){
            return response()->json(['message' =>'Report Successfully Submmitted', 'data' => $report]);
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
            'transfer' => $transfer,
            'total' => $total,
            'submittedDate' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
        ]);

        if($report){
            DailyReport::truncate();
            return response()->json(['message' =>'Weekly Report Successfully Submitted', 'data' => $report]);
        }
        return response()->json(['message' =>'Error']);
    }

    //update weekly report
     public function updateWeeklyReport(Request $request, $id){
        $report = WeeklyReport::find($id);
        
        if($report){
            $report->update($request->all());
            return response()->json([
                'message' => 'Report Successfully Edited',
                'data' => $report
            ]);
        }
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
            'transfer' => $transfer,
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

    //update daily report
    public function updateMonthlyReport(Request $request, $id){
        $report = MonthlyReport::find($id);
        
        if($report){
            $report->update($request->all());
            return response()->json([
                'message' => 'Report Successfully Edited',
                'data' => $report
            ]);
        }
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
            'transfer' => $transfer,
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
