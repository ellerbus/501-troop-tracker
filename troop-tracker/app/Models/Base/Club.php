<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Club
 * 
 * @property int $id
 * @property string $name
 * @property string $image_path
 * @property string $db_status_field
 * @property string $db_identifier_field
 * @property string $db_identifier_display
 * @property bool $active
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
}
