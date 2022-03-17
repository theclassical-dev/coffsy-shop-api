<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Tea;
use Carbon\Carbon;
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
            $ind['abbreviation'] = ucwords($request->input('abbreviation'));
            $ind['size'] = ucwords($request->input('size'));
            $ind['price'] = $request->input('price');

            $tea->update($ind);

            return response()->json(['message' => 'Successfully Updated']);
        }

        return response()->json(['message' => 'Record Not Found']);

    }
}
