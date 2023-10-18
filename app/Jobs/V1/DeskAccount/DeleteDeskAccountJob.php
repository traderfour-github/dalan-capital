<?php

namespace App\Jobs\V1\DeskAccount;

use App\Events\V1\DeskAccount\DeskAccountDeleteEvent;
use App\Jobs\SyncJob;
use App\Repositories\V1\Desk\Account\IDeskAccountRepository;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class DeleteDeskAccountJob extends SyncJob
{
    private IDeskAccountRepository $repository;

    /**
     * @param string $uuid
     *
     * @throws BindingResolutionException
     */
    public function __construct(private readonly string $uuid)
    {
        $this->repository = app()->make(IDeskAccountRepository::class);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function handle() : string
    {
        try {
            $result = $this->repository->destroy($this->uuid);
            if ( $result )
                event(new DeskAccountDeleteEvent($this->uuid));
            return $result;
        } catch ( Exception $e ) {
            throw new Exception($e->getMessage());
        }
    }
}
