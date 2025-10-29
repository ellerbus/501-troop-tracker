<?php

namespace App\Models;

use App\Models\Base\Squad as BaseSquad;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Squad extends BaseSquad
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'image_path',
        'active'
    ];
}
