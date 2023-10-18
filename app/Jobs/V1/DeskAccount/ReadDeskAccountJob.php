<?php

namespace App\Jobs\V1\DeskAccount;

use App\Jobs\SyncJob;
use App\Models\DalanCapital\V1\DeskAccount;
use App\Repositories\V1\Desk\Account\IDeskAccountRepository;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class ReadDeskAccountJob extends SyncJob
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
     * @return DeskAccount
     * @throws Exception
     */
    public function handle() : DeskAccount
    {
        try {
            return $this->repository->show($this->uuid);
        } catch ( Exception $e ) {
            throw new Exception($e->getMessage());
        }
    }
}
