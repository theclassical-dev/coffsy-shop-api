<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Tea;
use App\Models\BankDetail;
use Carbon\Carbon;
use Validator;
use DB;
use Auth;

class MainController extends Controller
{
    public function index() {
        return auth()->guard('staff')->user()->name;
    }

    public function allOrders(Request $request){

        $orders = Order::all();

        if($orders){
            return response()->json([
                'data' => $orders
            ]);
        }

        return response()->json(['message' => 'Orders not found']);
    }

    public function confirmPayment($id){

        $order = Order::find($id);

        if($order){
            $ind['pay_confirmation'] = ucwords('confirmed');
            $ind['pay_confirm_dateTime'] = Carbon::now('WAT')->format('l jS \of F Y h:i:s');

            // Order::create([
            //     'pay_confirm_dateTime' => Carbon::now(),
            // ]);

            $order->update($ind);

            return response()->json(['message' => 'Order payment successful confirmed']);
            
        }

        return response()->json(['message' => 'Error']);

    }

    public function orderStatus($id){

        $status = Order::find($id);

        if($status){
            $ind['status'] = ucwords('delivered');
            $ind['delivered_dateTime'] = Carbon::now('WAT')->format('l jS \of F Y h:i:s');

            $status->update($ind);

            return response()->json(['message' => 'Order Delivered Successfully']);
            
        }

        return response()->json(['message' => 'Error']);

    }

    public function createTeaType(Request $request) {

        $request->validate([
            'name' => 'required',
            'abbreviation' => 'required',
            'size' => 'required',
            'price' => 'required',
        ]);

        $tea = Tea::create([
            'name' => $request->input('name'),
            'abbreviation' => $request->input('abbreviation'),
            'size' => $request->input('size'),
            'price' => $request->input('price')
        ]);

        if($tea){
            return response()->json([
                'message' => 'Successfully Created',
                'data' => $tea
            ]);
        }

        return response()->json([
            'message' => 'Internal Error',
        ]);
    }

    public function updateTeaType(Request $request, $id){

        $tea = Tea::find($id);

        if($tea){
            $ind['name'] = ucwords($request->input('name'));
            $ind['abbreviation'] = strtoupper($request->input('abbreviation'));
            $ind['size'] = ucwords($request->input('size'));
            $ind['price'] = $request->input('price');

            $tea->update($ind);

            return response()->json(['message' => 'Successfully Updated']);
        }

        return response()->json(['message' => 'Record Not Found']);

    }

    public function deleteTeaType($id){

        $tea = Tea::find($id);
        if($tea){

            $tea->delete($tea);
            return response()->json(['message' => 'Record Successfully Deleted']);
            
        }

        return response()->json(['message' => 'Record Not Found']);
    }

    public function bankDetail(Request $request){
        $request->validate([
            'coff_id' => 'required|unique:bank_details,coff_id',
            'name' => 'required|string|unique:bank_details',
            'acct_name' => 'required|string|unique:bank_details',
            'acct_number' => 'required|integer|unique:bank_details',
            'bank' => 'required',
            'acct_type' => 'required',
        ]);

        //check for coff_id && name

        $staff = Staff::where('coff_id', $request->coff_id)->first();
        if(!$staff || !$staff->name){
            return response()->json(['message' => 'Staff ID No. or Name Provided Not Found']);
            exit();
        }

        $bank = BankDetail::create([
            'coff_id' => $request->input('coff_id'),
            'name' => ucwords($request->input('name')),
            'acct_name' => ucwords($request->input('acct_name')),
            'acct_number' => $request->input('acct_number'),
            'bank' => ucwords($request->input('bank')),
            'acct_type' => ucwords($request->input('acct_type'))
        ]);


        if($bank) {
            return response()->json([
                'message' => 'Bank Record Successfully Added',
                'data' => $bank
            ]);
        }
        return response()->json(['message' => 'Error']);

    }

    public function updateBankDetail(Request $request, $id){

        $request->validate([
            'coff_id' => 'required',
            'name' => 'required',
            'acct_name' => 'required',
            'acct_number' => 'required',
            'bank' => 'required',
            'acct_type' => 'required',
        ]);

        $bank = BankDetail::find($id);

        if($bank){
        
            $bank->update([
                'acct_name' => ucwords($request->get('acct_name')),
                'acct_number' => $request->get('acct_number'),
                'bank' => ucwords($request->get('bank')),
                'acct_type' => ucwords($request->get('acct_type')),
            ]);

            return response()->json([
                'message' => 'Record updated successfully',
                'data' => $bank
            ]);
        }
            return response()->json(['message' => 'Record not Found']);
    }

    public function deleteBankDetail($id){
        
        $bank = BankDetail::find($id);
        if($bank){

            $bank->delete($bank);
            return response()->json(['message' => 'Record Successfully deleted']);
        }
            return response()->json(['message' => 'Error']);
    }

    //create
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
}
