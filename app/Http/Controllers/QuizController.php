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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use OpenAI\Client;


class QuizController extends Controller
{




    public function generateQuiz(Request $request)
    {
        // Validate the uploaded PDF file
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);
    
        // Extract text from the PDF
        $pdfParser = new Parser();
        $pdfFile = $request->file('pdf');
        $pdfText = $pdfParser->parseFile($pdfFile->getRealPath())->getText();
    
        // Hardcoded SMS-style input
        $hardcodedSms = "Create a quiz from the following text. The quiz should contain 5 questions, each with 4 correct answers, and each answer should have a score.";
    
        // Combine the hardcoded SMS and PDF text to create the prompt
        $prompt = $hardcodedSms . "\n\nHere is the text:\n" . $pdfText;
    
        // Correct endpoint for chat-completion models
        $url = 'https://api.openai.com/v1/chat/completions';
    
        // Prepare the headers with the OpenAI API key
        $headers = [
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ];
    
        // Prepare the payload for the OpenAI API
        $data = [
            'model' => 'gpt-4', // You can use 'gpt-4' or other models as needed
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 1000,
            'temperature' => 0.7
        ];
    
        // Send the request to OpenAI API using Laravel's HTTP client (Guzzle)
        try {
            $response = Http::withHeaders($headers)
                ->post($url, $data); // Send POST request to OpenAI
    
            // Check if the response is successful
            if ($response->successful()) {
                $quiz = $response->json()['choices'][0]['message']['content'];
    
                return response()->json([
                    'success' => true,
                    'quiz' => $quiz,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to generate quiz: ' . $response->body(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    


    // https://api.openai.com/v1/chat/completions 
    // public function generateQuiz(Request $request)
    // {
    //     // Validate the uploaded PDF file
    //     $request->validate([
    //         'pdf' => 'required|file|mimes:pdf',
    //     ]);

    //     // Extract text from the PDF
    //     $pdfParser = new Parser();
    //     $pdfFile = $request->file('pdf');
    //     $pdfText = $pdfParser->parseFile($pdfFile->getRealPath())->getText();

    //     // Hardcoded SMS-style input
    //     $hardcodedSms = "Create a quiz from the following text. The quiz should contain 5 questions, each with 4 correct answers, and each answer should have a score.";

    //     // Combine the hardcoded SMS and PDF text to create the prompt
    //     $prompt = $hardcodedSms . "\n\nHere is the text:\n" . $pdfText;

    //     // Connect to OpenAI and generate the quiz
    //     $client = new Client(['api_key' => env('OPENAI_API_KEY')]);

    //     try {
    //         $response = $client->completions()->create([
    //             'model' => 'gpt-4o-mini', // Adjust as needed
    //             'prompt' => $prompt,
    //             'max_tokens' => 1000,
    //             'temperature' => 0.7,
    //         ]);

    //         $quiz = $response['choices'][0]['text'];

    //         return response()->json([
    //             'success' => true,
    //             'quiz' => $quiz,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
  

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

    public function show($id)
    {
      
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json(['error' => 'Quiz not found.'], 404);
        }

       
        $quizQuestions = QuizQuestion::where('quiz_id', $quiz->id)->get();

        
        $questionsWithAnswers = $quizQuestions->map(function ($question) {
            $answers = QuizQuestionAnswer::where('quiz_question_id', $question->id)->get();
            
            
            return [
                'question' => $question->text,
                'answers' => $answers->pluck('text'), 
            ];
        });

        return response()->json([
            'quiz' => $quiz->name, 
            'questions' => $questionsWithAnswers
        ]);
    }

    

   
}
