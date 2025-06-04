<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tests', App\Http\Controllers\TestController::class);
Route::resource('patients', App\Http\Controllers\PatientController::class);
Route::resource('services', App\Http\Controllers\ServiceController::class);
Route::resource('doctors', App\Http\Controllers\DoctorController::class);
Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
Route::resource('suppliers', App\Http\Controllers\SupplierController::class);