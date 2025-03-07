<?php

namespace App\Jobs;

use App\Models\Raid;
use App\Models\Encounter;
use App\Services\RaidService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateRaidsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $raidService;

    public function __construct(RaidService $raidService)
    {
        $this->raidService = $raidService;
    }

    public function handle()
    {
        $raids = $this->raidService->getRaids();

        
    }
}
