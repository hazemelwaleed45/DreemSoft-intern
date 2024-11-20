<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['consultant_id', 'day', 'from', 'to'];

    public function consultant()
    {
        return $this->belongsTo(Consultant::class);
    }

    // public function slots()
    // {
    //     return $this->hasMany(Slot::class);
    // }
}
