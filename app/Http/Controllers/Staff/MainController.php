<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Tea;
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

    public function confirmPayment(Request $request, $id){

        $order = Order::find($id);

        if($order){
            $ind['pay_confirmation'] = ucwords('confirmed');

            $order->update($ind);

            return response()->json(['message' => 'Order payment successful confirmed']);
            
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
}
