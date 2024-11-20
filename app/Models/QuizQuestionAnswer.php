<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestionAnswer extends Model
{
    use HasFactory;
    protected $fillable = ['quiz_question_id', 'text', 'score'];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    public function results()
    {
        return $this->hasMany(QuizQuestionResult::class, 'quiz_question_answer_id');
    }
}
