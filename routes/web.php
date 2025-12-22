<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|//
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Halaman Depan
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Baca Artikel (Pakai Slug biar link-nya cantik)
// Contoh: website.com/baca/cara-install-laravel
Route::get('/baca/{post:slug}', [HomeController::class, 'show'])->name('post.show');
