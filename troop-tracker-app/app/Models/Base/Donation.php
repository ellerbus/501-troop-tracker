<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Donation
 * 
 * @property int $id
 * @property int $trooper_id
 * @property float $amount
 * @property string $txn_id
 * @property string $txn_type
 * @property Carbon $donated_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class Donation extends Model
{
    const ID = 'id';
    const TROOPER_ID = 'trooper_id';
    const AMOUNT = 'amount';
    const TXN_ID = 'txn_id';
    const TXN_TYPE = 'txn_type';
    const DONATED_AT = 'donated_at';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_donations';

    protected $casts = [
        self::ID => 'int',
        self::TROOPER_ID => 'int',
        self::AMOUNT => 'float',
        self::DONATED_AT => 'datetime',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }
}
