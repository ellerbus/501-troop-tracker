<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Trooper;

/**
 * Service for managing Trooper-related operations,
 * particularly for retrieving Trooper data.
 */
class TrooperService
{
    /**
     * TrooperService constructor.
     *
     * @param ClubService $clubs The service for club-related operations.
     */
    public function __construct(private readonly ClubService $clubs)
    {
    }

    /**
     * Retrieves a Trooper by their forum username.
     *
     * @param string $username The forum username of the trooper.
     * @return Trooper|null The Trooper model if found, otherwise null.
     */
    public function getByForumUsername(string $username): ?Trooper
    {
        return Trooper::where('forum_id', $username)->first();
    }

    /**
     * Checks if a club-specific identifier is unique within the troopers table.
     *
     * This is used to validate identifiers like a Mando Mercs CAT # or a
     * Saber Guild SG # to ensure they are not already assigned to another trooper.
     *
     * @param int $club_id The ID of the club to check against.
     * @param mixed $identifier The identifier value to check for uniqueness.
     * @return bool True if the identifier is unique, false otherwise.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function isUniqueClubIdentifier(int $club_id, mixed $identifier): bool
    {
        $club = $this->clubs->getById($club_id);

        $exists = Trooper::where($club->db_identifier_field, $identifier)->exists();

        return !$exists;
    }

    public function register(array $data): Trooper
    {
        $trooper = new Trooper();

        $trooper->name = $data['name'];
        $trooper->email = $data['email'];
        $trooper->phone = $data['phone'] ?? null;
        $trooper->forum_id = $data['forum_username'];
        $trooper->password = password_hash($data['forum_password'], PASSWORD_DEFAULT);


        // Loop through selected clubs and assign identifiers
        foreach ($data['clubs'] ?? [] as $club_id => $club_data)
        {
            if (!empty($club_data['selected']))
            {
                // You’ll need to map club-specific fields to trooper columns
                // Example: if club uses 'tkid' as identifier field
                $club = $this->clubs->getById($club_id);

                if ($club && !empty($club->db_identifier_field) && !empty($club_data['identifier']))
                {
                    $trooper->{$club->db_identifier_field} = $club_data['identifier'];
                }

                if (!empty($club_data['squad_id']))
                {
                    $trooper->squad = $club_data['squad_id'];
                }
            }
        }

        $trooper->save();

        return $trooper;



    }
}