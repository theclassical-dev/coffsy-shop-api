<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Staff;
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
}
