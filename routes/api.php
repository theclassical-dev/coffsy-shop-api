<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthController;
use App\Http\Controllers\Users\CustomerController;
use App\Http\Controllers\Admin\BossController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Staff\MainController;
use App\Http\Controllers\Staff\StaffController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// registreation route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/staff/register', [StaffController::class, 'register']);

// login routes
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/staff/login', [StaffController::class, 'login']);

//users routes
Route::group(['middleware' => ['auth:user'], 'prefix' => 'user/v1', 'namespace' => 'Users'], function () {
    Route::get('/test',[CustomerController::class, 'index']);
});
//staff routes
Route::group(['middleware' => ['auth:staff'], 'prefix' => 'staff/v1', 'namespace' => 'Staff'], function () {
    Route::get('/test',[MainController::class, 'index']);
    Route::post('/logout',[StaffController::class, 'logout']);
});
//admin routes
Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin/v1', 'namespace' => 'Admin'], function () {
    Route::get('/test',[BossController::class, 'index']);
});
