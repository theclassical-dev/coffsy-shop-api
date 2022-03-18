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

    public function salary(Request $request){
         $request->validate([
             'name' => 'required|unique:salaries, name',
             'coff_id' => 'required||unique:salaries, coff_id',
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
}
