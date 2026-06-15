<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::view('/login', 'login')->name('login');
Route::view('/register', 'register')->name('register');
// Pelanggan (User) routes
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/lapangan', 'lapangan')->name('lapangan.index');

// Admin routes
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
Route::view('/admin/lapangan', 'admin.lapangan')->name('admin.lapangan');
Route::view('/admin/bookings', 'admin.bookings')->name('admin.bookings');
