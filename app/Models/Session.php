<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $fillable = ['programm_id', 'title', 'description'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
