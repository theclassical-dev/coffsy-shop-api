<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $c = random_bytes(1);
        $y = date('y');
        $m = date('m');
        $coff = 'CF'.$y.strtoupper((bin2hex($c))).$m;

        $user = User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'coff_id' => $coff,
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
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
        $user =  User::where('email', $data['email'])->first();

        // $user = User::where('coff_id', $data['email'])->first();
        //check password
        if(!$user || !Hash::check($data['password'], $user->password)){
            return response(['message' => 'check the inserted details'], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request){

        auth()->user()->tokens()->delete();

        return [
            'message' => 'logged out'
        ];
    }
}
