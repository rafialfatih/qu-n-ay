<?php

use App\Http\Controllers\Answer\AnswerController;
use App\Http\Controllers\Auth\AuthenticateUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Question\QuestionController;
use App\Http\Controllers\Question\QuestionSearchController;
use App\Http\Controllers\Question\QuestionVoteController;
use App\Http\Controllers\User\UserController;
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
    Route::get('register', [RegisterUserController::class, 'create'])
      ->name('auth.register');
    Route::post('register', [RegisterUserController::class, 'store'])
      ->name('auth.store');

    Route::get('login', [AuthenticateUserController::class, 'create'])
      ->name('auth.create');
});

Route::middleware('auth')->group(function () {
    Route::resource('questions', QuestionController::class)
      ->except(['show', 'index', 'edit'])
      ->names([
          'create' => 'question.create',
          'store' => 'question.store',
          'update' => 'question.update',
          'destroy' => 'question.destroy',
      ]);
    Route::get('questions/{question}/{slug?}/edit', [QuestionController::class, 'edit'])
      ->name('question.edit');

    Route::post('answers', [AnswerController::class, 'store'])
      ->name('answer.store');
    Route::put('answers/{answer}', [AnswerController::class, 'update'])
      ->name('answer.update');
    Route::get('questions/{question}/answers/{answer}/edit', [AnswerController::class, 'edit'])
      ->name('answer.edit');
    Route::put('questions/{question}/answers/{answer}', [AnswerController::class, 'update'])
      ->name('answer.update');
    Route::delete('questions/{question}/answers/{answer}', [AnswerController::class, 'destroy'])
      ->name('answer.delete');

    Route::post('question-vote', QuestionVoteController::class)
      ->name('question_vote');

    Route::delete('logout', [AuthenticateUserController::class, 'destroy'])
      ->name('auth.logout');
});

Route::get('questions', [QuestionController::class, 'index'])
  ->name('question.index');
Route::get('questions/{question}/{slug?}', [QuestionController::class, 'show'])
  ->name('question.show');

Route::get('search', QuestionSearchController::class)
  ->name('question.search');

Route::get('user/{user:username}/', [UserController::class, 'show'])
  ->name('user.show');

Route::post('login', [AuthenticateUserController::class, 'store'])
  ->name('auth.login');
