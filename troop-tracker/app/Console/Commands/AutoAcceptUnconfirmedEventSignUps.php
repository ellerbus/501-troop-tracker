<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Repositories\TrooperStatsRepository;
use Illuminate\Console\Command;

/**
 * This file is used for auto accepting unconfirmed troops every 6 months to clear up the database
 */
class AutoAcceptUnconfirmedEventSignUps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-accept-unconfirmed-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically accept unconfirmed events.';

    public function __construct(private readonly TrooperStatsRepository $event_sign_ups)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->event_sign_ups->autoConfirm();
    }
}
