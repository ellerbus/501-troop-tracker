<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Squad;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Club
 * 
 * @property int $id
 * @property string $name
 * @property string $image_path
 * @property string $db_status_field
 * @property string $db_identifier_field
 * @property string $identifier_display
 * @property string|null $identifier_validation
 * @property bool $active
 * 
 * @property Collection|Squad[] $squads
 *
 * @package App\Models\Base
 */
class Club extends Model
{
    protected $table = 'clubs';
    public $timestamps = false;

    protected $casts = [
        'active' => 'bool'
    ];

    public function squads()
    {
        return $this->hasMany(Squad::class);
    }
}
