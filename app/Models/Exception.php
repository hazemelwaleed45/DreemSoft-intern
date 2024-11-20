<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exception extends Model
{
    
    use HasFactory;

    protected $fillable = ['consultant_id', 'day', 'from', 'to', 'status'];

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }
}
