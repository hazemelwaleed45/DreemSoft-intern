<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = ['session_id', 'title'];

    protected $attributes = [
        'questionsNumber' => 5,
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function feedback()
    {
        return $this->hasMany(QuizFeedback::class);
    }

    public function actions()
    {
        return $this->hasMany(QuizAction::class);
    }
}
