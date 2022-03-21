<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tea;

class PublicController extends Controller
{
    public function getAllTea(){
        
        $tea = Tea::all();

        if(!$tea->isEmpty()){
            return response()->json([
                'data' => $tea
            ]);
        }

        return response()->json(['message' => 'No Record Found']);
        
    }
}
