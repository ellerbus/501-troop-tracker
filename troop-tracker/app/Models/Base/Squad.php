<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Squad
 * 
 * @property int $id
 * @property int $club_id
 * @property string $name
 * @property string|null $image_path
 * @property bool $active
 * 
 * @property Club $club
 *
 * @package App\Models\Base
 */
class Squad extends Model
{
    protected $table = 'squads';
    public $timestamps = false;

    protected $casts = [
        'club_id' => 'int',
        'active' => 'bool'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
