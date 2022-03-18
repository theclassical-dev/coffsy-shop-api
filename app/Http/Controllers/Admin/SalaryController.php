<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\SalaryHistory;

class SalaryController extends Controller
{
    public function index(){

        return response()->json(['data' => Salary::all()]);
    }
    //
    public function salary(Request $request){
         $request->validate([
             'name' => 'required|unique:salaries, name',
             'coff_id' => 'required|unique:salaries, coff_id',
             'position' => 'required',
             'amount' => 'required',
         ]);

         $salary = Salary::create([
             'name' => $request->input('name'),
             'coff_id' => $request->input('coff_id'),
             'position' => $request->input('position'),
             'amount' => $request->input('amount'),
            ]);

        if($salary) {

            return response()->json([
                'message' => 'Staff Record Successfully Added',
                'data' => $salary
            ]);
        }
    }

    //
    public function allPaid(Request $request){
        $request->validate([
            'month' => 'required'
        ]);

        $s = Salary::all();
        $history = SalaryHistory::create([

            'name' => $s->name,
            'coff_id' => $s->coff_id,
            'position' => $s->position,
            'amount' => $s->amount,
            'status' => 'Paid',
            'month' => $request->input('month'),
            'date' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')

        ]);

        if($history){
            return response()->json([
                'message' => 'Records Successfully Uploaded',
            ]);
        }
            return response()->json([
                'message' => 'Error',
            ]);
    }

    public function paid($id){

        $request->validate([
            'month' => 'required'
        ]);

        $s = Salary::find($id);

        if($s){

            SalaryHistory::create([
            'name' => $s->name,
            'coff_id' => $s->coff_id,
            'position' => $s->position,
            'amount' => $s->amount,
            'status' => 'Paid',
            'month' => $request->input('month'),
            'date' => Carbon::now('WAT')->format('l jS \of F Y h:i:s')
            ]);

            return response()->json(['message' => 'Record successfully Uploaded']);
        }
            return response()->json(['message' => 'Error',]);
    }
}
