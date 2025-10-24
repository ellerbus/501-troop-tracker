<?php

declare(strict_types=1);

namespace App\Domain\Models;

use \DateTime;

class TrooperModel extends BaseModel
{
    protected static string $tableName = 'troopers';

    public int $id; // PRIMARY KEY, auto_increment
    public int $user_id = 0;
    public string $name;
    public ?string $email = null;
    public ?string $phone = null;
    public int $squad;
    public int $permissions = 0;

    public int $spTrooper = 0;
    public int $spCostume = 0;
    public int $spAward = 0;

    public int $p501 = 0;
    public int $pRebel = 0;
    public int $pDroid = 0;
    public int $pMando = 0;
    public int $pOther = 0;
    public ?int $pSG = 0;
    public ?int $pDE = 0;

    public string $tkid;
    public string $forum_id;
    public ?string $rebelforum = null;
    public ?int $mandoid = null;
    public string $sgid = '0';
    public int $de_id = 0;
    public ?string $password = null;

    public DateTime $last_active; // DEFAULT CURRENT_TIMESTAMP
    public int $approved = 0;
    public int $subscribe = 1;
    public int $theme = 0;
    public int $supporter = 0;

    public int $esquad0 = 1;
    public ?int $esquad1 = 1;
    public ?int $esquad2 = 1;
    public ?int $esquad3 = 1;
    public ?int $esquad4 = 1;
    public ?int $esquad5 = 1;

    public int $esquad6 = 1;
    public int $esquad7 = 1;
    public int $esquad8 = 1;
    public int $esquad9 = 1;
    public int $esquad10 = 1;
    public int $esquad13 = 1;

    public ?int $efast = 0;
    public ?int $ecommandnotify = 1;
    public ?int $econfirm = 1;

    public ?string $note = null;
    public DateTime $datecreated; // DEFAULT CURRENT_TIMESTAMP

    /**
     * Checks if the trooper is a Super Admin.
     *
     * @return boolean
     */
    public function isSuperAdmin(): bool
    {
        return $this->permissions === 1;
    }

    /**
     * Checks if the trooper is a Moderator.
     *
     * @return boolean
     */
    public function isModerator(): bool
    {
        return $this->permissions === 2;
    }

    /**
     * Checks if the trooper has administrative privileges (Super Admin or Moderator).
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isModerator();
    }

    /**
     * Checks if the trooper is marked as RIP (Rest In Peace).
     *
     * @return boolean
     */
    public function isRetired(): bool
    {
        return $this->permissions === 3;
    }

    public function canAccess(): bool
    {
        return !$this->isRetired();
    }
}