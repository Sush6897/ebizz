<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::get('/', [UserController::class, 'showLoginForm'])->name('login.form');
Route::post('/login1', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/dashboard/{year?}',[ExpenseController::class,'index'])->name('dashboard');

Route::get('/importview',[ExpenseController::class,'importForm']);


Route::post('/import',[ExpenseController::class,'import']);

Route::get('/expenses/{id}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');

Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->name('expenses.update');

Route::delete('/expenses/{id}/delete', [ExpenseController::class, 'destroy'])->name('expenses.destroy');














Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::post('/expenses', [ExpenseController::class, 'store']);

