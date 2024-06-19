<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Meeting extends Model
{
    use HasFactory;
    const STATUS_ENG_INIT = 'eng_init';
    const STATUS_USER_BOOK = 'user_book';
    const STATUS_MEETING_FINISHED = 'meeting_finished';
    const STATUS_REVIEW_SET = 'review_set';

    protected $fillable = [
        'start_at',
        'user_id',
        'eng_id',
        'url',
        'rating',
        'status',
        'session_id'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function eng()
    {
        return $this->belongsTo(User::class, 'eng_id');
    }
}
