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
    Route::get('register', [RegisterUserController::class, 'create'])->name('register.create');
    Route::post('register', [RegisterUserController::class, 'store'])->name('register.post');

    Route::get('login', [AuthenticateUserController::class, 'create'])->name('auth.create');
});

Route::middleware('auth')->group(function () {
    Route::resource('questions', QuestionController::class)
        ->except(['show', 'index', 'edit'])
        ->names([
            'create' => 'question.create',
            'store' => 'question.store',
            'update' => 'question.update',
            'destroy' => 'question.destroy'
        ]);
    Route::get('questions/{question}/{slug?}/edit', [QuestionController::class, 'edit'])->name('question.edit');

    Route::post('logout', [AuthenticateUserController::class, 'destroy'])->name('auth.destroy');
});

Route::get('questions', [QuestionController::class, 'index'])->name('question.index');
Route::get('questions/{question}/{slug?}', [QuestionController::class, 'show'])->name('question.show');

Route::post('login', [AuthenticateUserController::class, 'store'])->name('auth.post');
