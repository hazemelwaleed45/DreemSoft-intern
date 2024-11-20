<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizFeedback extends Model
{
    use HasFactory;
    protected $fillable = ['quiz_id', 'min_grade', 'max_grade', 'feedback'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    
}
