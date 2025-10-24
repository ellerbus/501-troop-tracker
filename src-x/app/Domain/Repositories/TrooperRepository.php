<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\TrooperModel;

class TrooperRepository extends BaseRepository
{
    protected string $modelClass = TrooperModel::class;

    public function getByForumUsername(string $forumId): ?TrooperModel
    {
        $sql = "SELECT * FROM trooper WHERE forum_id = :forum_id LIMIT 1";

        $data = $this->db->fetchOne($sql, [$forumId]);

        return $this->inflate($data);
    }
}