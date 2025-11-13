<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\TrooperCostume;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClubCostume
 * 
 * @property int $id
 * @property int $club_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Club $club
 * @property Collection|TrooperCostume[] $trooper_costumes
 *
 * @package App\Models\Base
 */
class ClubCostume extends Model
{
    const ID = 'id';
    const CLUB_ID = 'club_id';
    const NAME = 'name';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_club_costumes';

    protected $casts = [
        self::ID => 'int',
        self::CLUB_ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function trooper_costumes()
    {
        return $this->hasMany(TrooperCostume::class, TrooperCostume::COSTUME_ID);
    }
}
