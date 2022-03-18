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
            'total' => 'required',
        ]);

        $report = DailyReport::create([
            'delivered' => $report->input('delivered'),
            'cash' => $report->input('cash'),
            'trasfer' => $report->input('trasfer'),
            'total' => $report->input('total'),
            'submittedDate' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
        ]);
    }
}
