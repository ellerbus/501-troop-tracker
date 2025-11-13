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
        'troopers_status_field',
        'troopers_identifier_field'
    ];

    public function costumes()
    {
        return $this->hasMany(Costume::class, 'club_id', 'id');
    }
}
