<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PetController::class, "get"])->name('dashboard');
Route::post('/pet/create', [PetController::class, "create"])->name('pet.create');
Route::post('/pet/edit', [PetController::class, "edit"])->name('pet.edit');
Route::post('/pet/delete', [PetController::class, "delete"])->name('pet.delete');
Route::post('/error', function () {
    return view('error');
})->name('error');
