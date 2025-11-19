<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantUploadController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/tenant-upload', [TenantUploadController::class, 'upload'])->name('tenant-upload');
