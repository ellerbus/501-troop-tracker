<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Organization;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperOrganization
 * 
 * @property int $id
 * @property int $trooper_id
 * @property int $organization_id
 * @property string $identifier
 * @property bool $notify
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Organization $organization
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class TrooperOrganization extends Model
{
    const ID = 'id';
    const TROOPER_ID = 'trooper_id';
    const ORGANIZATION_ID = 'organization_id';
    const IDENTIFIER = 'identifier';
    const NOTIFY = 'notify';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_trooper_organizations';

    protected $casts = [
        self::ID => 'int',
        self::TROOPER_ID => 'int',
        self::ORGANIZATION_ID => 'int',
        self::NOTIFY => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }
}
