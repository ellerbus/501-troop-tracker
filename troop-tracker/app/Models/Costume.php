<?php

namespace App\Models;

use App\Models\Base\Costume as BaseCostume;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Costume extends BaseCostume
{
    use HasFactory;

    protected $fillable = [
        'costume',
        'club',
        'club_id'
    ];

    public function owningClub()
    {
        return $this->belongsTo(Club::class, 'club_id', 'id');
    }
}
