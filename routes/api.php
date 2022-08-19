<?php

use App\Http\Controllers\Api\V1\ChargeCodeController;
use App\Http\Controllers\Api\V1\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix("v1")->group(function () {
    Route::prefix("wallet")->name("wallet.")->group(function () {
        Route::get("balance", [WalletController::class, "balance"])->name("balance");
        Route::get("transactions", [WalletController::class, "transactions"])->name("transactions");
        Route::post("charge", [WalletController::class, "charge"])->name("charge");
    });
    Route::apiResource("charge-codes", ChargeCodeController::class)->only(['index', "show", "store"]);
});
