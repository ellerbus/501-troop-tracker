<?php

namespace App\Models;

use App\Models\Base\Club as BaseClub;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends BaseClub
{
    use HasFactory;

    /**
     * Used for UI
     * 
     * @var bool
     */
    public bool $selected = false;

    protected $fillable = [
        'name',
        'image_path',
        'db_status_field',
        'db_identifier_field'
    ];
}
