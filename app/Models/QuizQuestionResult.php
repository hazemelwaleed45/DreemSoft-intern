<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestionResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id' ,
        'quiz_id', 
        'question_id', 
        'answer_id'
    ];

    public function quizResult()
    {
        return $this->belongsTo(QuizResult::class);
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    public function answer()
    {
        return $this->belongsTo(QuizQuestionAnswer::class, 'quiz_question_answer_id');
    }
}
