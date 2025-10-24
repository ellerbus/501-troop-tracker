<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Models\TrooperModel;
use App\Domain\Repositories\TrooperRepository;
use App\Payloads\LoginPayload;
use App\Domain\Results\LoginResult;
use App\Domain\Results\LoginStatus;
use App\Utilities\Configuration;
use App\Utilities\HttpClient;
use Exception;
use PDO;

/**
 * Class AuthenticationService.
 *
 * Handles user authentication logic, including login and logout processes.
 */
class TrooperService
{
    /**
     * AuthenticationService constructor.
     *
     * @param PDO $db The database connection.
     */
    public function __construct(
        private readonly PDO $db,
        private readonly Configuration $configuration,
        private readonly TrooperRepository $repo,
    ) {
    }

    public function synchronize(): void
    {
    }
}