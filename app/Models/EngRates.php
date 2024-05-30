<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngRates extends Model
{
    use HasFactory;
    protected $fillable = [
        'meeting_rate',
        'overall_rating',
        'eng_id',
    ];
}
