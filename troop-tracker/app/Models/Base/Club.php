<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Squad;
use App\Models\Trooper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Club
 * 
 * @property int $id
 * @property string $name
 * @property string $image_path
 * @property string|null $identifier_display
 * @property string|null $identifier_validation
 * @property bool $active
 * @property string|null $troopers_status_field
 * @property string|null $troopers_identifier_field
 * @property string|null $troopers_notification_field
 * @property int|null $troop_tracker_value
 * 
 * @property Collection|Squad[] $squads
 * @property Collection|Trooper[] $troopers
 *
 * @package App\Models\Base
 */
class Club extends Model
{
    protected $table = 'clubs';
    public $timestamps = false;

    protected $casts = [
        'active' => 'bool',
        'troop_tracker_value' => 'int'
    ];

    public function squads()
    {
        return $this->hasMany(Squad::class);
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'trooper_clubs')
                    ->withPivot('id', 'notify', 'membership_status');
    }
}
