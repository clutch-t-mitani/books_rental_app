<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\admin\AdminRentalBookController;
use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\RankingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [bookController::class, 'index'])->name('books.index');
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');

Route::middleware('auth')->group(function() {
    Route::post('/', [bookController::class, 'store'])->name('books.store');
    Route::get('/mypage', [UserController::class, 'index'])->name('user.mypage');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart_delete', [CartController::class, 'delete'])->name('cart.delete');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
});

Auth::routes();


Route::middleware('auth:admin')->name('admin.')->prefix('admin')->group(function() {
    Route::get('/rental_books', [AdminRentalBookController::class, 'index'])->name('index');
    Route::post('/rental_books', [AdminRentalBookController::class, 'update'])->name('update');
    Route::get('/books', [AdminBookController::class, 'index'])->name('book');
    Route::post('/books', [AdminBookController::class, 'update'])->name('book.update');
    Route::post('/categories/store', [AdminCategoryController::class, 'store'])->name('category.store');
    Route::post('/categories', [AdminCategoryController::class, 'update'])->name('category.update');
    Route::post('/books/create', [AdminBookController::class, 'create'])->name('book.create');
    Route::post('/books/delete', [AdminBookController::class, 'delete'])->name('book.delete');
});

Route::prefix('admin')->group(function() {
    Route::view('/login', 'admin/login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class,'logout']);
    Route::view('/register', 'admin/register');
    Route::post('/register', [RegisterController::class, 'register']);
});

