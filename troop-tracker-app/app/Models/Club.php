<?php

namespace App\Models;

use App\Models\Base\Club as BaseClub;
use App\Models\Scopes\HasClubScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;

class Club extends BaseClub
{
    use HasFactory;
    use HasClubScopes;

    protected $fillable = [
        self::NAME,
        self::IMAGE_PATH_LG,
        self::IMAGE_PATH_SM,
        self::IDENTIFIER_DISPLAY,
        self::IDENTIFIER_VALIDATION,
        self::ACTIVE
    ];

    protected function casts(): array
    {
        return array_merge($this->casts, [
        ]);
    }
}
// ...

//     /**
//      * Scope to retrieve all active clubs, ordered by name.
//      *
//      * @param Builder $query
//      * @param bool $withSquads
//      * @param bool $withCostumes
//      * @return Builder
//      */
//     public function scopeActive(Builder $query, bool $withSquads = false, bool $withCostumes = false): Builder
//     {
//         $query->where(self::ACTIVE, true)->orderBy(self::NAME);

//         if ($withSquads) {
//             $query->with('squads');
//         }

//         if ($withCostumes) {
//             $query->with('costumes');
//         }

//         return $query;
//     }

//     /**
//      * Retrieves all active clubs, optionally eager-loading relationships.
//      *
//      * @param bool $includeSquads
//      * @param bool $includeCostumes
//      * @return Collection<int, Club>
//      */
//     public static function findAllActive(bool $includeSquads = false, bool $includeCostumes = false): Collection
//     {
//         return once(fn() =>
//             self::active($includeSquads, $includeCostumes)->get()
//         );
//     }
// }
