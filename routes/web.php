<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LearningPathController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\RecommendationController;




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


Route::get('/exercise/completed', [ExerciseController::class, 'completed'])->name('exercise.completed');

Route::post('/api/submit-exercise', [ExerciseController::class, 'submitExercise']);


Route::get('/api/remaining-exercises', [ExerciseController::class, 'getRemainingExercises']);

Route::get('/exercise', [ExerciseController::class, 'showExercise'])->name('exercise.show');

Route::post('/exercise/submit', [ExerciseController::class, 'submitExercise'])->name('exercise.submit');


Route::get('/learning-path/{user}', [LearningPathController::class, 'show'])->name('your.learning.path');


Route::get('/subscription/upgrade', [SubscriptionController::class, 'showUpgradePage'])->name('subscription.upgrade');


Route::get('/leaderboard', [LeaderboardController::class, 'showLeaderboard'])->name('leaderboard');


Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');


Route::post('/generate-feedback', [OpenAIController::class, 'generateFeedback']);

Route::post('/submit-text', [SubmissionController::class, 'store'])->name('submit.text');


Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard')->middleware('auth');


Route::post('/generate-question', [OpenAIController::class, 'generateQuestion']);


Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');

Route::post('/export-pdf', [SubmissionController::class, 'exportPdf'])->name('export.pdf');


Route::post('/generate-mark', [OpenAIController::class, 'generateMark']);

Route::get('/recommendations/{assessmentNo}', [RecommendationController::class, 'recommendCourses']);





require __DIR__.'/auth.php';
