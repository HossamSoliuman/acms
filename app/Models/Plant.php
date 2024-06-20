<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Plant extends Model
{
    use HasFactory;
    const PathToStoredImages='plant/images/covers';
    protected $fillable=[
			'name',
			'description',
			'cover',
    ];

}
