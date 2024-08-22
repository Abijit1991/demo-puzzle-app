<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->prefix('student')->group(function () {
    // Student Home Page API.
    Route::get('/home', [Controllers\Student\HomeController::class, 'index'])
        ->name('student.home');

    // All Puzzle Display Page API .
    Route::get('/puzzle/{puzzle_id}', [Controllers\Student\PuzzleController::class, 'showPuzzle'])
        ->name('student.showpuzzle');

    // Puzzle Response Save API .
    Route::post('/puzzle/response/submit', [Controllers\Student\PuzzleController::class, 'savePuzzleResponse'])
        ->name('student.save.puzzle.response');

    // Specific Puzzle Top Scores Page API.
    Route::get('/puzzle/toppers/{puzzle_id}/', [Controllers\Student\PuzzleController::class, 'showPuzzleToppers'])
        ->name('student.showpuzzlestoppers');

    // Overall Puzzle Top Scores Page API.
    Route::get('/topperslist', [Controllers\Student\PuzzleController::class, 'showToppersList'])
        ->name('student.topperslist');
});
