<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// INDEX ROUTE -> REDIRECT TO BOOKS.INDEX
Route::get('/', function () {
    return redirect()->route('books.index');
});


// BOOKS routes used by BookController.php
Route::resource('books', BookController::class)
    ->only(['index', 'create', 'store', 'show', 'destroy']); // Only use defined routes


// REVIEWS routes used by ReviewController.php
Route::resource('books.reviews', ReviewController::class) // reviews belong to books
    ->scoped(['review' => 'book']) // review is scoped from book
    ->only(['create', 'store']);
