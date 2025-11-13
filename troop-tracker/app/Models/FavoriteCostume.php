<?php

namespace App\Models;

use App\Models\Base\FavoriteCostume as BaseFavoriteCostume;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FavoriteCostume extends BaseFavoriteCostume
{
    use HasFactory;

    protected $fillable = [
        'trooperid',
        'costumeid'
    ];

    public function getCostumeName(): string
    {
        return "(" . $this->costume->owningClub->name . ") " . $this->costume->costume;
    }

    public function costume()
    {
        return $this->belongsTo(Costume::class, 'costumeid', 'id');
    }
}
