<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthTokenController;
use App\Http\Controllers\IpAddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthTokenController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('ip-addresses')->group(function () {
        Route::get('/', [IpAddressController::class, 'index']);
        Route::post('/', [IpAddressController::class, 'store']);
        Route::patch('/{ipAddress}', [IpAddressController::class, 'update']);
    });

    Route::post('/logout', [AuthTokenController::class, 'logout']);
});
