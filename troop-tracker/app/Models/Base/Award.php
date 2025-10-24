<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Award
 * 
 * @property int $id
 * @property string $title
 * @property string $icon
 *
 * @package App\Models\Base
 */
class Award extends Model
{
    protected $table = 'awards';
    public $timestamps = false;
}
