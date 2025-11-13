<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * 
 * @property int $id
 * @property bool $site_closed
 * @property int $support_goal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models\Base
 */
class Setting extends Model
{
    const ID = 'id';
    const SITE_CLOSED = 'site_closed';
    const SUPPORT_GOAL = 'support_goal';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_settings';

    protected $casts = [
        self::ID => 'int',
        self::SITE_CLOSED => 'bool',
        self::SUPPORT_GOAL => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];
}
