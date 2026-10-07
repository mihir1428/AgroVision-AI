<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DiseaseController as AdminDiseaseController;
use App\Http\Controllers\Admin\PredictionController as AdminPredictionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/login',[AuthController::class,'showLogin'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->middleware('throttle:10,1');
    Route::get('/register',[AuthController::class,'showRegister'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->middleware('throttle:5,1');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/dashboard',DashboardController::class)->name('dashboard');
    Route::get('/scan',[PredictionController::class,'create'])->name('predictions.create');
    Route::post('/scan',[PredictionController::class,'store'])->name('predictions.store')->middleware('throttle:20,1');
    Route::get('/history',[PredictionController::class,'index'])->name('predictions.index');
    Route::get('/predictions/{prediction}',[PredictionController::class,'show'])->name('predictions.show');
    Route::delete('/predictions/{prediction}',[PredictionController::class,'destroy'])->name('predictions.destroy');
    Route::post('/predictions/{prediction}/feedback',[FeedbackController::class,'store'])->name('feedback.store');
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/',AdminDashboardController::class)->name('dashboard');
        Route::resource('diseases',AdminDiseaseController::class)->except('show');
        Route::get('predictions',[AdminPredictionController::class,'index'])->name('predictions.index');
    });
});
