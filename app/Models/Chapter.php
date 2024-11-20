<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $fillable = ['programm_id', 'title', 'description'];

    public function programm()
    {
        return $this->belongsTo(Programm::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
