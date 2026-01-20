<?php

use App\Http\Controllers\Api\WorksheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'API is working']);
});

Route::post('/worksheets', [WorksheetController::class, 'store'])->name('worksheet.store');