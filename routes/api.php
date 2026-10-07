<?php
use App\Http\Controllers\Api\PredictionController;
use Illuminate\Support\Facades\Route;
Route::post('/predict', PredictionController::class)->middleware('throttle:30,1');
Route::get('/health', fn () => ['status'=>'ok','app'=>'AgroVision AI']);
