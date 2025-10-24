<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Donation
 * 
 * @property int $trooperid
 * @property float $amount
 * @property string $txn_id
 * @property string $txn_type
 * @property Carbon $datetime
 *
 * @package App\Models\Base
 */
class Donation extends Model
{
    protected $table = 'donations';
    protected $primaryKey = 'txn_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'trooperid' => 'int',
        'amount' => 'float',
        'datetime' => 'datetime'
    ];
}
