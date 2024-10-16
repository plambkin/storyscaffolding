<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\LearningPathController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\AccountabilityContractController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/accountability', [AssessmentController::class, 'start'])->name('accountability.start');

Route::post('/accountability/submit', [AssessmentController::class, 'submit'])->name('accountability.submit');


Route::get('/accountability-contract', [AccountabilityContractController::class, 'show'])->name('accountability.contract');

Route::post('/accountability-contract', [AccountabilityContractController::class, 'accept'])->name('accountability.contract.accept');


Route::get('/assessment/completed', [AssessmentController::class, 'completed'])->name('assessment.completed');

Route::get('/assessment', [AssessmentController::class, 'showExercise'])->name('assessment.show');

Route::post('/assessment/submit', [AssessmentController::class, 'submitExercise'])->name('assessment.submit');

Route::post('/api/submit-exercise', [AssessmentController::class, 'submitExercise']);

Route::get('/api/remaining-exercises', [AssessmentController::class, 'getRemainingExercises']);

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
