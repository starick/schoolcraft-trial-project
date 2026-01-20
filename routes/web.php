<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::group(['prefix' => 'worksheets', 'as' => 'worksheet.'], function () {
    Route::livewire('/', 'pages::worksheet.index')->name('index');
});

require __DIR__ . '/settings.php';
