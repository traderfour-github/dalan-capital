<?php

namespace App\Jobs\V1\my\Team;

use App\Events\V1\Team\UpdateEvent;
use App\Jobs\SyncJob;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Repositories\V1\my\Team\ITeamRepository as MyITeamRepository;

class UpdateJob extends SyncJob
{
    private MyITeamRepository $teamRepository;

    /**
     * Create a new job instance.
     * @throws BindingResolutionException
     */
    public function __construct(
        public array $data,
        public string $uuid,
    )
    {
        $this->teamRepository = app()->make(MyITeamRepository::class);
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): array
    {
        try {

            $result = $this->teamRepository->updateByUuid($this->data, $this->uuid);

            $team = [];

            if ($result) {
                $team = $this->teamRepository->singleByUuid($this->uuid);
                event(new UpdateEvent($team));
            }

            return $team;

        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
}
