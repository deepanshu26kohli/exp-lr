<?php

use App\Http\Controllers\HeadController;
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
    Route::get('/head',[HeadController::class,'gethead']);
    Route::post('/head',[HeadController::class,'store']);
});



//Head Api


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
