<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\Trooper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Squad
 * 
 * @property int $id
 * @property int $club_id
 * @property string $name
 * @property string|null $image_path
 * @property bool $active
 * @property string|null $troopers_notification_field
 * @property int|null $troop_tracker_value
 * 
 * @property Club $club
 * @property Collection|Trooper[] $troopers
 *
 * @package App\Models\Base
 */
class Squad extends Model
{
    protected $table = 'squads';
    public $timestamps = false;

    protected $casts = [
        'club_id' => 'int',
        'active' => 'bool',
        'troop_tracker_value' => 'int'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'trooper_squads')
                    ->withPivot('id', 'notify', 'membership_status');
    }
}
