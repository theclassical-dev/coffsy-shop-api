<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:staffs,email',
            'position' => 'required',
            'password' => 'required|string|confirmed'
        ]);

        $c = random_bytes(1);
        $y = date('y');
        $m = date('m');
        $coff = 'SCF'.$y.strtoupper((bin2hex($c))).$m;

        $staff = Staff::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'coff_id' => $coff,
            'position' => $data['position'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $staff->createToken('stafftoken')->plainTextToken;

        $response = [
            'staff' => $staff,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //check email
        // $staff =  Staff::where('coff_id', $data['email'])->first();
        $staff =  Staff::where('coff_id', $data['email'])->first();

        //check password
        if(!$staff || !Hash::check($data['password'], $staff->password)){
            return response(['message' => 'check the inserted details'], 401);
        }
        $token = $staff->createToken('stafftoken')->plainTextToken;

        $response = [
            'staff' => $staff,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request){

        auth()->guard('staff')->user()->tokens()->delete();

        return response()->json([
            'message' => 'Log out'
        ]);
    }
}
