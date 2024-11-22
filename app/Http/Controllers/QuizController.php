<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestionResult;
use App\Models\QuizResult;
use App\Models\QuizQuestionAnswer ;
use App\Models\QuizQuestion ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class QuizController extends Controller
{
    public function answerQuiz(Request $request, $quiz_id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.answer_id' => 'required|exists:quiz_question_answers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // dd($validator);
        $data = $validator->validated();

        $totalQuestions = QuizQuestion::where('quiz_id', $quiz_id)->count();
        $answeredQuestions = collect($data['answers'])->pluck('question_id')->unique()->count();

        //dd($totalQuestions , $answeredQuestions);
        if ($answeredQuestions < $totalQuestions) {
            return response()->json([
                'error' => 'You must complete the quiz by answering all questions.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $score = 0;
            $answeredQuestions = []; 

            foreach ($data['answers'] as $answer) {
                $question = QuizQuestion::find($answer['question_id']);

                
                if ($question->type === 'single') {
                    if (isset($answeredQuestions[$question->id])) {
                        return response()->json([
                            'error' => "Question ID {$question->id} is a single-answer question and already answered."
                        ], 400);
                    }
                    $answeredQuestions[$question->id] = true;
                }

                $answer_id = $answer['answer_id'];
                $answer_obj = QuizQuestionAnswer::find($answer_id);
                $score += $answer_obj->score;

                QuizQuestionResult::create([
                    'user_id' => $data['user_id'],
                    'quiz_id' => $quiz_id,
                    'question_id' => $answer['question_id'],
                    'answer_id' => $answer_id,
                ]);
            }
            QuizResult::create([
                'user_id' => $data['user_id'],
                'quiz_id' => $quiz_id,
                'score' => $score,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Quiz submitted successfully.',
                'score' => $score
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to submit quiz.'], 500);
        }
    }


    public function listQuizResults($quiz_id)
    {
        $results = QuizResult::where('quiz_id', $quiz_id)->get();
        return response()->json($results);
    }

    public function quizResultsAnalytic($quiz_id)
    {
        $analytics = DB::table('quiz_question_results as qqr')
            ->join('quiz_questions as qq', 'qq.id', '=', 'qqr.question_id')
            ->join('quiz_question_answers as qa', 'qa.id', '=', 'qqr.answer_id')
            ->select(
                'qq.text as question_text', 
                'qa.text as answer_text', 
                DB::raw('count(*) as num_users')
            )
            ->where('qqr.quiz_id', $quiz_id)
            ->groupBy('qq.id', 'qa.id')
            ->get();
    
        return response()->json($analytics);
    }
    

   
}
