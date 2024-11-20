<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programm extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description'];

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
