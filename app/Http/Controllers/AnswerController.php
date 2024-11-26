<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestionAnswer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function show($id)
    {
        
        $quizQuestionAnswer = QuizQuestionAnswer::find($id);

       
        if (!$quizQuestionAnswer) {
            return response()->json(['error' => 'Question not found.'], 404);
        }

       
        $rondomQuestions = $quizQuestionAnswer->text;

        // dd($rondomQuestions);
        $questions = explode("\n", $rondomQuestions);

        $randomQuestion = $questions[array_rand($questions)];

        $quizQuestionAnswer->text = $randomQuestion;

     
        return response()->json([
            'id' => $quizQuestionAnswer->id,
            'text' => $quizQuestionAnswer->text,
            'created_at' => $quizQuestionAnswer->created_at,
            'updated_at' => $quizQuestionAnswer->updated_at,
        ]);
    }
}
