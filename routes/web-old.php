<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ProfileController;



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


Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');


Route::post('/generate-feedback', [OpenAIController::class, 'generateFeedback']);

Route::post('/submit-text', [SubmissionController::class, 'store'])->name('submit.text');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::post('/generate-question', [OpenAIController::class, 'generateQuestion']);


Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');

Route::post('/export-pdf', [SubmissionController::class, 'exportPdf'])->name('export.pdf');

Route::get('/leaderboard', function () {
    return view('leaderboard');
})->name('leaderboard');

Route::post('/generate-mark', [OpenAIController::class, 'generateMark']);




require __DIR__.'/auth.php';
