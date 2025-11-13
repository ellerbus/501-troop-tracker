<?php

namespace App\Models;

use App\Models\Base\Donation as BaseDonation;

class Donation extends BaseDonation
{
	protected $fillable = [
		self::TROOPER_ID,
		self::AMOUNT,
		self::TXN_ID,
		self::TXN_TYPE
	];
}
