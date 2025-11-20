<?php

namespace App\Models;

use App\Models\Base\Costume as BaseCostume;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Costume extends BaseCostume
{
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::ORGANIZATION_ID,
        self::NAME,
    ];

    public function fullCostumeName(): string
    {
        return "({$this->organization->name}) {$this->name}";
    }
}
