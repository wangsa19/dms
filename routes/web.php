<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
Route::get('/documents', App\Livewire\Admin\Documents::class)->name('documents');
Route::get('/sign-in', App\Livewire\Auth\SignIn::class)->name('sign-in');