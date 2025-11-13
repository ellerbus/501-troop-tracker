<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\MembershipStatus;
use App\Models\Trooper;
use App\Models\TrooperClub;

/**
 * Repository for managing Trooper-related operations,
 * particularly for retrieving Trooper data.
 */
class TrooperRepository
{
    /**
     * TrooperRepository constructor.
     * @param ClubRepository $clubs The repository for club-related operations.
     */
    public function __construct(private readonly ClubRepository $clubs)
    {
    }

    /**
     * Finds a Trooper by its primary key.
     *
     * @param int $id The primary key of the trooper.
     * @return Trooper|null The Trooper model if found, otherwise null.
     */
    public function getById(int $id): ?Trooper
    {
        return Trooper::find($id);
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
     * Checks if a club-specific identifier exists within the troopers table.
     *
     * This is used to validate identifiers like a Mando Mercs CAT # or a
     * Saber Guild SG # to ensure they are not already assigned to another trooper.
     *
     * @param int $club_id The ID of the club to check against.
     * @param mixed $identifier The identifier value to check for uniqueness.
     * @return bool True if the identifier exists, false otherwise.
     *
     */
    public function clubIdentifierExists(int $club_id, mixed $identifier): bool
    {
        //  TODO LEGACY CLEAN UP
        $club = $this->clubs->getById($club_id);

        $exists = Trooper::where($club->troopers_identifier_field, $identifier)->exists();

        if (!$exists)
        {
            $exists = TrooperClub::where('club_id', $club_id)
                ->where('identifier', $identifier)
                ->exists();
        }

        return $exists;
    }

    /**
     * Registers a new trooper in the system.
     *
     * This method creates a new Trooper record, assigns general and club-specific
     * data, sets their initial membership status, and saves the record to the database.
     *
     * @param array $data An associative array containing the trooper's registration data.
     * @param mixed $auth_user_id The ID of the authenticated user from the external forum.
     * @return Trooper The newly created Trooper model instance.
     */
    public function register(array $data, mixed $auth_user_id): Trooper
    {
        //  TODO LEGACY CLEAN UP
        $trooper = new Trooper();

        $trooper->name = $data['name'];
        $trooper->email = $data['email'];
        $trooper->phone = $data['phone'] ?? null;
        $trooper->user_id = $auth_user_id;
        $trooper->forum_id = $data['username'];
        $trooper->password = password_hash($data['password'], PASSWORD_DEFAULT);

        $trooper->squad = -1;    //  gets populated via $data inputs
        $trooper->tkid = 'X-' . uniqid();    //  gets populated via $data inputs

        $trooper->save();

        $membership_status = $data['account_type'] == 1 ? MembershipStatus::Member : MembershipStatus::Handler;

        // Loop through selected clubs and assign identifiers
        foreach ($data['clubs'] ?? [] as $club_id => $club_data)
        {
            if (!empty($club_data['selected']))
            {
                // You’ll need to map club-specific fields to trooper columns
                // Example: if club uses 'tkid' as identifier field
                $club = $this->clubs->getById($club_id);

                if ($club && !empty($club->troopers_identifier_field) && !empty($club_data['identifier']))
                {
                    $trooper->{$club->troopers_status_field} = $membership_status;
                    $trooper->{$club->troopers_identifier_field} = $club_data['identifier'];

                    $trooper->trooperClubs()->create([
                        'club_id' => $club->id,
                        'identifier' => $club_data['identifier'],
                        'notify' => true,
                        'membership_status' => $membership_status
                    ]);
                }

                if (isset($club_data['squad_id']))
                {
                    $trooper->squad = $club_data['squad_id'];

                    $trooper->trooperSquads()->create([
                        'squad_id' => $club_data['squad_id'],
                        'notify' => true,
                        'membership_status' => $membership_status
                    ]);
                }
            }
        }

        // save again cause of the legacy columns
        $trooper->save();

        return $trooper;
    }
}