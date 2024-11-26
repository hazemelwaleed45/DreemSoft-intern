<?php

use App\Http\Controllers\AnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultantController ;

use App\Http\Controllers\ProgramController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizResultController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/consultants', [ConsultantController::class, 'index']);

Route::get('/consultant-booking/{id}/{Date}', [ConsultantController::class, 'getConsultantDetails']);
Route::get('/consultant-booking/{id}', [ConsultantController::class, 'getConsultantAllSlots']);
Route::get('/consultant-showWeekly/{id}', [ConsultantController::class, 'GETSLOTS']);
Route::get('/consultants/{id}', [ConsultantController::class, 'show']);

Route::get('/consultants/{consultantId}/weekly-slots', [ConsultantController::class, 'getWeeklySlots']);


Route::get('answerDetails/{id}', [AnswerController::class, 'show']);
Route::get('questionDetails/{id}', [QuestionController::class, 'show']);


Route::get('programms', [ProgramController::class, 'getProgramms']);
Route::get('programmDetails/{id}', [ProgramController::class, 'getProgrammDetails']);

Route::post('quiz-submit/{quiz_id}', [QuizController::class, 'answerQuiz']);
Route::get('quiz-results/{quiz_id}', [QuizController::class, 'listQuizResults']);
Route::get('quiz-analytics/{quiz_id}', [QuizController::class, 'quizResultsAnalytic']);



Route::post('generate-quiz', [QuizController::class, 'generateQuiz']);


//  Route::get('quiz-results-save/{quiz_id}', [QuizResultController::class, 'calculateQuizResults']);

