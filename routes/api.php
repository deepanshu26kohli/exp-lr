<?php

use App\Http\Controllers\HeadController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TypeOfTransactionController;
use App\Http\Controllers\UserController;
use App\Models\TypeOfTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Login Api
Route::post('/login',[UserController::class,'accessToken'])->name("login");


//Type of Transaction api
Route::group(['middleware' => ['web','auth:api']], function()
{
    Route::get('/typeoftransaction',[TypeOfTransactionController::class,'get_type_of_transaction']);
    //Head API
    Route::get('/head',[HeadController::class,'gethead']);
    Route::post('/add-head',[HeadController::class,'store']);
    Route::get('edit-head/{id}',[HeadController::class,'edit']);
    Route::put('update-head/{id}',[HeadController::class,'update']);
    Route::delete('delete-head/{id}',[HeadController::class,'destroy']);
    //Bank API
    Route::get('/bank',[BankController::class,'getbank']);
    Route::post('/add-bank',[BankController::class,'store']);
    Route::get('edit-bank/{id}',[BankController::class,'edit']);
    Route::put('update-bank/{id}',[BankController::class,'update']);
    Route::delete('delete-bank/{id}',[BankController::class,'destroy']);
    //Transaction API
    Route::get('/transaction',[TransactionController::class,'getTransaction']);
});



//Head Api


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
