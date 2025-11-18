<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Trooper;
use App\Models\TrooperAward;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Award
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Trooper[] $troopers
 *
 * @package App\Models\Base
 */
class Award extends Model
{
    const ID = 'id';
    const NAME = 'name';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_awards';

    protected $casts = [
        self::ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_trooper_awards')
                    ->withPivot(TrooperAward::ID)
                    ->withTimestamps();
    }
}
