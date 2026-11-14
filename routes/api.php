<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
Route::get('orders-summary',[OrderController::class,'summary']);
Route::apiResource('orders',OrderController::class)->middleware('throttle:120,1');
