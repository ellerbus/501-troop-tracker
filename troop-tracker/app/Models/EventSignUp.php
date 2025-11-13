<?php

namespace App\Models;

use App\Models\Base\EventSignUp as BaseEventSignUp;

class EventSignUp extends BaseEventSignUp
{
    protected $fillable = [
        'trooperid',
        'troopid',
        'costume',
        'costume_backup',
        'status',
        'addedby',
        'note',
        'signuptime'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'troopid', 'id');
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class, 'trooperid', 'id');
    }

    public function costume()
    {
        return $this->belongsTo(Costume::class, 'costume', 'id');
    }
}
