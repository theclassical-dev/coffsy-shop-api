<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoCode;

class BossController extends Controller
{
    public function index(){
        return auth()->guard('admin')->user()->name;

    }

    public function promoCode(Request $request){

        $request->validate([
            'percentage' => 'required',
            'days' => 'required'
        ]);

        $code = strtoupper((bin2hex(random_bytes(4))));

        $promo = PromoCode::create([
            'code' => $code,
            'percentage' => $request->input('percentage'),
            'days' => $request->input('days')
        ]);

        if($promo){
            return response()->json([
                'message' => 'successfully created',
                'data' => $promo
            ]);
        }

            return response()->json(['message' => 'Error']);
    }
}
