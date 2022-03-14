<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BossController extends Controller
{
    public function index(){
        return auth()->guard('admin')->user()->name;
    }
}
