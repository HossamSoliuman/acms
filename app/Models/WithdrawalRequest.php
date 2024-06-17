<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class WithdrawalRequest extends Model
{
	use HasFactory;

	const STATUS_PENDING = 'pending';
	const STATUS_VERIFIED = 'verified';
	const STATUS_CANCELED = 'canceled';
	const STATUS_FAILED = 'failed';
	const STATUS_SUCCEEDED = 'succeeded';

	protected $fillable = [
		'user_id',
		'amount',
		'method',
		'details',
		'status',

	];



	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
