<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PetController::class, "get"])->name('dashboard');
Route::post('/pet/create', [PetController::class, "create"])->name('pet.create');
Route::post('/error', function () {
    return view('error');
})->name('error');
