<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Product extends Model
{
    use HasFactory;
    const PathToStoredImages = 'product/images/covers';
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
        'cover',
    ];

	public function orderItems(){
		return $this->hasMany(OrderItems::class);
	}
}