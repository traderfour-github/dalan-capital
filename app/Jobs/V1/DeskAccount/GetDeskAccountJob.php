<?php

namespace App\Jobs\V1\DeskAccount;

use App\Jobs\SyncJob;
use App\Repositories\V1\Desk\Account\IDeskAccountRepository;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;

class GetDeskAccountJob extends SyncJob
{
    private IDeskAccountRepository $repository;

    /**
     * @param array $attributes
     *
     * @throws BindingResolutionException
     */
    public function __construct(private readonly array $attributes)
    {
        $this->repository = app()->make(IDeskAccountRepository::class);
    }

    /**
     * @return Collection
     * @throws Exception
     */
    public function handle() : Collection
    {
        try {
            return $this->repository->index($this->attributes);
        } catch ( Exception $e ) {
            throw new Exception($e->getMessage());
        }
    }
}
