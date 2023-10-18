<?php

namespace App\Events\V1\my\Contract;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContractDeleteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  string  $contractId
     */
    public function __construct(
        public string $contractId
    ) {
    }
}
