<?php

use App\Http\Controllers\AuthTokenController;
use App\Http\Controllers\IpAddressController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthTokenController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('ip-addresses')->group(function () {
        Route::get('/', [IpAddressController::class, 'index']);
        Route::post('/', [IpAddressController::class, 'store']);
        Route::patch('/{ipAddress}', [IpAddressController::class, 'update']);
    });
});
