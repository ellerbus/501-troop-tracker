<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Title
 * 
 * @property int $id
 * @property string $title
 * @property string $icon
 * @property int $forum_id
 *
 * @package App\Models\Base
 */
class Title extends Model
{
    protected $table = 'titles';
    public $timestamps = false;

    protected $casts = [
        'forum_id' => 'int'
    ];
}
