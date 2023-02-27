<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminRentalBookController;
use App\Http\Controllers\AdminBookController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [bookController::class, 'index'])->name('books.index');

Route::group(['middleware' => 'auth'], function() {
    Route::post('/', [bookController::class, 'store'])->name('books.store');
    Route::get('/mypage', [UserController::class, 'index'])->name('user.mypage');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart_delete', [CartController::class, 'delete'])->name('cart.delete');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth:admin'], function() {
    Route::get('/admin/rental_books', [AdminRentalBookController::class, 'index'])->name('admin.index');
    Route::post('/admin/rental_books', [AdminRentalBookController::class, 'update'])->name('admin.update');
    Route::get('/admin/books', [AdminBookController::class, 'index'])->name('admin.book');
    Route::post('/admin/books', [AdminBookController::class, 'update'])->name('admin.book.update');
    Route::post('/admin/books/delete', [AdminBookController::class, 'delete'])->name('admin.book.delete');
});


Route::view('/admin/login', 'admin/login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('admin/logout', [LoginController::class,'logout']);
Route::view('/admin/register', 'admin/register');
Route::post('/admin/register', [RegisterController::class, 'register']);
Route::view('/admin/home', 'admin/home')->middleware('auth:admin');
