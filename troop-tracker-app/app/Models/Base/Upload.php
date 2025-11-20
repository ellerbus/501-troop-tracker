<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Upload
 * 
 * @property int $id
 * @property int $troopid
 * @property int $trooperid
 * @property string|null $filename
 * @property int $admin
 * @property Carbon $date
 *
 * @package App\Models\Base
 */
class Upload extends Model
{
    const ID = 'id';
    const TROOPID = 'troopid';
    const TROOPERID = 'trooperid';
    const FILENAME = 'filename';
    const ADMIN = 'admin';
    const DATE = 'date';
    protected $table = 'uploads';
    public $timestamps = false;

    protected $casts = [
        self::ID => 'int',
        self::TROOPID => 'int',
        self::TROOPERID => 'int',
        self::ADMIN => 'int',
        self::DATE => 'datetime'
    ];
}
