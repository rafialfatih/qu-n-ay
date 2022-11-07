<?php

use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Question\QuestionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'create']);
    Route::post('/register', [RegisterUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::resource('questions', QuestionController::class)->except('index', 'show');
    Route::post('/logout', [AuthenticateUserController::class, 'destroy']);
});

Route::get('/questions', [QuestionController::class, 'index']);
Route::get('/questions/{question}/{slug?}', [QuestionController::class, 'show']);

Route::get('/login', [AuthenticateUserController::class, 'create'])->name('login');
Route::post('/login', [AuthenticateUserController::class, 'store']);
