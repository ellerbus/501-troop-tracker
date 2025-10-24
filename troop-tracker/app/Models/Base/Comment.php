<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * 
 * @property int $id
 * @property int $troopid
 * @property int $trooperid
 * @property int $post_id
 * @property string $comment
 * @property Carbon $posted
 *
 * @package App\Models\Base
 */
class Comment extends Model
{
    protected $table = 'comments';
    public $timestamps = false;

    protected $casts = [
        'troopid' => 'int',
        'trooperid' => 'int',
        'post_id' => 'int',
        'posted' => 'datetime'
    ];
}
