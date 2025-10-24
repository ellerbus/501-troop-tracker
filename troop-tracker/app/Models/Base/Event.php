<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * 
 * @property int $id
 * @property int $thread_id
 * @property int $post_id
 * @property string $name
 * @property string|null $venue
 * @property Carbon|null $dateStart
 * @property Carbon|null $dateEnd
 * @property string|null $website
 * @property int|null $numberOfAttend
 * @property int|null $requestedNumber
 * @property string|null $requestedCharacter
 * @property bool|null $secureChanging
 * @property bool|null $blasters
 * @property bool|null $lightsabers
 * @property bool|null $parking
 * @property bool|null $mobility
 * @property string|null $amenities
 * @property string|null $referred
 * @property string|null $poc
 * @property string|null $comments
 * @property string|null $location
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $label
 * @property string|null $postComment
 * @property string|null $notes
 * @property bool|null $limitedEvent
 * @property int $limitRebels
 * @property int $limit501st
 * @property int $limitMando
 * @property int $limitDroid
 * @property int $limitOther
 * @property int $limitSG
 * @property int $limitDE
 * @property int $limitTotalTroopers
 * @property int $limitHandlers
 * @property int $friendLimit
 * @property int $allowTentative
 * @property bool $closed
 * @property int $charityDirectFunds
 * @property int $charityIndirectFunds
 * @property string|null $charityName
 * @property int|null $charityAddHours
 * @property string|null $charityNote
 * @property int $squad
 * @property int $link
 * @property int $link2
 *
 * @package App\Models\Base
 */
class Event extends Model
{
    protected $table = 'events';
    public $timestamps = false;

    protected $casts = [
        'thread_id' => 'int',
        'post_id' => 'int',
        'dateStart' => 'datetime',
        'dateEnd' => 'datetime',
        'numberOfAttend' => 'int',
        'requestedNumber' => 'int',
        'secureChanging' => 'bool',
        'blasters' => 'bool',
        'lightsabers' => 'bool',
        'parking' => 'bool',
        'mobility' => 'bool',
        'limitedEvent' => 'bool',
        'limitRebels' => 'int',
        'limit501st' => 'int',
        'limitMando' => 'int',
        'limitDroid' => 'int',
        'limitOther' => 'int',
        'limitSG' => 'int',
        'limitDE' => 'int',
        'limitTotalTroopers' => 'int',
        'limitHandlers' => 'int',
        'friendLimit' => 'int',
        'allowTentative' => 'int',
        'closed' => 'bool',
        'charityDirectFunds' => 'int',
        'charityIndirectFunds' => 'int',
        'charityAddHours' => 'int',
        'squad' => 'int',
        'link' => 'int',
        'link2' => 'int'
    ];
}
