<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request){

       $order =  auth()->user()->order;
        if(!$order->isEmpty()){

            return response()->json([
                'data' => $order
            ]);
        }

        return response()->json(['message' => 'no order available']);
       
    }

    public function order(Request $request){
        $request->validate([
            'tea_type' => 'required',
            'description' => 'required',
            'address' => 'required',
            'promo_code' => 'nullable',
            'pay_type' => 'required',
            'price' => 'required',
        ]);

        $name = $request->input('firstName').' '.$request->input('lastName');
        // user fullname  
        $n = auth()->user()->firstName.' '.auth()->user()->lastName;
        //create order
        $order = Order::create([
            'name' => $n,
            'coff_id' => auth()->user()->coff_id,
            'tea_type' => $request->input('tea_type'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),
            'promo_code' => $request->input('promo_code'),
            'pay_type' => $request->input('pay_type'),
            'price' => $request->input('price'),
            'pay_confirmation' => 'Pending...',
            'status' => 'Processing..'
        ]);
        //check if request is successful 
        if($order){
            return response()->json([
                'message' => 'Order Successful Created, Processing...',
                'data' => $order
            ]);
        }

        return response()->json([
            'message' => 'Order Failed'
        ]);
    }
}
