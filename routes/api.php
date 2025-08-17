<?php

use App\Http\Controllers\Api\v1\{
    AuthController,
    UserController,
    TellerController,
    TransactionController,
    Denomination,
    DenominationController,
    TransactionDenominationController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Users
    Route::apiResource('users', UserController::class);
    
    // Tellers
    Route::apiResource('tellers', TellerController::class);
    
    // Transactions
    Route::apiResource('transactions', TransactionController::class);
    
    // Denominations
    Route::apiResource('denominations', DenominationController::class);
    
    // Transaction Denominations
    Route::apiResource('transaction-denominations', TransactionDenominationController::class);
});