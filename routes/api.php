<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthController;
use App\Http\Controllers\Users\CustomerController;
use App\Http\Controllers\Users\OrderController;
use App\Http\Controllers\Admin\BossController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Staff\MainController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\ReportController;
use App\Http\Controllers\PublicController;


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
Route::get('/getAllTea', [PublicController::class, 'getAllTea']);
// login routes
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/staff/login', [StaffController::class, 'login']);

//users routes
Route::group(['middleware' => ['auth:user'], 'prefix' => 'user/v1', 'namespace' => 'Users'], function () {
    Route::get('/test',[CustomerController::class, 'index']);
    Route::get('/my-orders',[OrderController::class, 'index']);
    Route::post('/place-order',[OrderController::class, 'order']);
    Route::delete('/cancel-order/{id}',[OrderController::class, 'cancelOrder']);
    Route::get('/logout',[CustomerController::class, 'logout']);
    
});
//staff routes
Route::group(['middleware' => ['auth:staff'], 'prefix' => 'staff/v1', 'namespace' => 'Staff'], function () {
    //
    Route::get('/all-orders',[MainController::class, 'allOrders']);
    //
    Route::put('/confirm-payment/{id}',[MainController::class, 'confirmPayment']);
    Route::put('/order-status/{id}',[MainController::class, 'orderStatus']);
    //
    Route::get('/test',[MainController::class, 'index']);
    //
    Route::post('/add-tea',[MainController::class, 'createTeaType']);
    Route::put('/update-tea/{id}',[MainController::class, 'updateTeaType']);
    Route::delete('/delete-tea/{id}',[MainController::class, 'deleteTeaType']);
    //
    Route::post('/add-staff-bank-details',[MainController::class, 'bankDetail']);
    Route::put('/update-staff-bank-details/{id}',[MainController::class, 'updateBankDetail']);
    Route::delete('/delete-staff-bank-details/{id}',[MainController::class, 'deleteBankDetail']);
    //
    Route::post('/create-report',[MainController::class, 'report']);
    Route::post('/create-weekly-report',[MainController::class, 'weeklyReport']);
    Route::post('/create-monthly-report',[MainController::class, 'monthlyReport']);
    Route::post('/create-yearly-report',[MainController::class, 'yearlyReport']);
    //
    Route::put('/update-report/{id}',[MainController::class, 'updateReport']);
    Route::put('/update-weekly-report/{id}',[MainController::class, 'updateWeeklyReport']);
    Route::put('/update-monthly-report/{id}',[MainController::class, 'updateMonthlyReport']);

    //
    Route::post('/logout',[ReportController::class, 'logout']);
    
    
});
//admin routes
Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin/v1', 'namespace' => 'Admin'], function () {
    Route::get('/test',[BossController::class, 'index']);
    Route::post('/logout',[AdminController::class, 'logout']);

});
