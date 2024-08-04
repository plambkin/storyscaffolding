<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\SubmissionController;



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

Route::get('/profile', function () {
    // Your logic for displaying the profile page
})->name('profile');

Route::post('/generate-feedback', [OpenAIController::class, 'generateFeedback']);

Route::post('/submit-text', [SubmissionController::class, 'store'])->name('submit.text');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::post('/generate-question', [OpenAIController::class, 'generateQuestion']);




require __DIR__.'/auth.php';
