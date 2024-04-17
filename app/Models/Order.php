<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Order extends Model
{
    use HasFactory;
    const STATU_PAID = 'paid';
    const STATU_UNPAID = 'unpaid';
    protected $fillable = [
        'shipping_address',
        'user_id',
        'status',
        'total_amount',
        'session_id',
    ];

    protected function shippingAddress(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


	public function orderItems(){
		return $this->hasMany(OrderItems::class);
	}
}