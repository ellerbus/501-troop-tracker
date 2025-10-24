<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Costume
 * 
 * @property int $id
 * @property string $costume
 * @property int $club
 *
 * @package App\Models\Base
 */
class Costume extends Model
{
    protected $table = 'costumes';
    public $timestamps = false;

    protected $casts = [
        'club' => 'int'
    ];
}
