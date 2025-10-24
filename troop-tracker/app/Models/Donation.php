<?php

namespace App\Models;

use App\Models\Base\Donation as BaseDonation;

class Donation extends BaseDonation
{
	protected $fillable = [
		'trooperid',
		'amount',
		'txn_type',
		'datetime'
	];
}
