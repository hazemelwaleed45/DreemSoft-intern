<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizQuestion ;

class QuestionController extends Controller
{
    public function show($id)
    {
        
        $quizQuestion = QuizQuestion::find($id);

       
        if (!$quizQuestion) {
            return response()->json(['error' => 'Question not found.'], 404);
        }

       
        $rondomQuestions = $quizQuestion->text;

        // dd($rondomQuestions);
        $questions = explode("\n", $rondomQuestions);

        $randomQuestion = $questions[array_rand($questions)];

        $quizQuestion->text = $randomQuestion;

     
        return response()->json([
            'id' => $quizQuestion->id,
            'text' => $quizQuestion->text,
            'created_at' => $quizQuestion->created_at,
            'updated_at' => $quizQuestion->updated_at,
        ]);
    }
}
