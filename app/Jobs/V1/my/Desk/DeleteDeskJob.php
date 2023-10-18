<?php

namespace App\Jobs\V1\my\Desk;

use App\Events\V1\my\Desk\DeskDeleteEvent;
use App\Repositories\V1\my\Desk\IDeskRepository;
use App\Jobs\SyncJob;
use Exception;

class DeleteDeskJob extends SyncJob
{
    private IDeskRepository $repository;

    /**
     * @param  string  $deskId
     */
    public function __construct(
        private string $deskId
    ) {
        $this->repository = app()->make(IDeskRepository::class);
    }

    /**
     *
     * @return string
     * @throws Exception
     */
    public function handle(): string
    {
        try {

            $desk = $this->repository->destroy($this->deskId);

            DeskDeleteEvent::dispatch($this->deskId);

            return $this->deskId;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
