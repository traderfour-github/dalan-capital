<?php

namespace App\Jobs\V1\DeskAccount;

use App\Events\V1\DeskAccount\DeskAccountStoreEvent;
use App\Jobs\SyncJob;
use App\Models\DalanCapital\V1\DeskAccount;
use App\Repositories\V1\Desk\Account\IDeskAccountRepository;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

class StoreDeskAccountJob extends SyncJob
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
     * @return DeskAccount
     * @throws Exception
     */
    public function handle() : DeskAccount
    {
        try {
            $result = $this->repository->create($this->attributes);
            if ( $result )
                event(new DeskAccountStoreEvent($result));
            return $result;
        } catch ( Exception $exception ) {
            throw new Exception($exception->getMessage());
        }
    }
}
