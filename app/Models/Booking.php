<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['consultant_id', 'day', 'from', 'to', 'user_id'];

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }
}
