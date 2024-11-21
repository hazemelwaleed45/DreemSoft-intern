<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizResult;
use App\Models\QuizQuestionAnswer ;
use Illuminate\Support\Facades\DB;

class QuizResultController extends Controller
{
    public function calculateQuizResults()
    {
        
        $results = DB::table('quiz_question_results as QQ')
            ->join('quiz_question_answers as A', 'A.id', '=', 'QQ.answer_id')
            ->select('QQ.user_id', 'QQ.quiz_id', DB::raw('SUM(A.score) as score'))
            ->groupBy('QQ.user_id', 'QQ.quiz_id')
            ->get();

        //dd($results);
        foreach ($results as $result) {
            // dd($result->Total);
            QuizResult::updateOrCreate(
                ['user_id' => $result->user_id, 
                'quiz_id' => $result->quiz_id,
                'score' => $result->score]
            );
            // dd(QuizResult::all() , $result->score);
        }

        return response()->json(['message' => 'Quiz results calculated and saved successfully']);
    }

}
