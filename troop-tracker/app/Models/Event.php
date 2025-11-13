<?php

namespace App\Models;

use App\Models\Base\Event as BaseEvent;

class Event extends BaseEvent
{
    protected $fillable = [
        'thread_id',
        'post_id',
        'name',
        'venue',
        'dateStart',
        'dateEnd',
        'website',
        'numberOfAttend',
        'requestedNumber',
        'requestedCharacter',
        'secureChanging',
        'blasters',
        'lightsabers',
        'parking',
        'mobility',
        'amenities',
        'referred',
        'poc',
        'comments',
        'location',
        'latitude',
        'longitude',
        'label',
        'postComment',
        'notes',
        'limitedEvent',
        'limitRebels',
        'limit501st',
        'limitMando',
        'limitDroid',
        'limitOther',
        'limitSG',
        'limitDE',
        'limitTotalTroopers',
        'limitHandlers',
        'friendLimit',
        'allowTentative',
        'closed',
        'charityDirectFunds',
        'charityIndirectFunds',
        'charityName',
        'charityAddHours',
        'charityNote',
        'squad',
        'link',
        'link2'
    ];

    public function owningSquad()
    {
        return $this->belongsTo(Squad::class, 'squad', 'id');
    }
}
