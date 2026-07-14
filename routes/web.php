<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AttendanceLogController; 
use Illuminate\Support\Facades\Route;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/attendance/checkin', fn() => redirect()->route('dashboard'))->name('attendance.checkin.get');
Route::post('/attendance/checkin', [AttendanceLogController::class, 'store'])->name('attendance.checkin');

Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
