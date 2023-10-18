<?php

namespace App\Jobs\V1\my\Team;

use App\Events\V1\Team\DeleteEvent;
use App\Jobs\SyncJob;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use App\Repositories\V1\my\Team\ITeamRepository as MyITeamRepository;

class DeleteJob extends SyncJob
{
    private MyITeamRepository $teamRepository;

    /**
     * Create a new job instance.
     * @throws BindingResolutionException
     */
    public function __construct(
        public string $uuid,
        public string $userUuid,
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

            $team = $this->teamRepository->singleByUuid($this->uuid);

            if ($team->user_id !== $this->userUuid){
                return false; //TODO : need to return exception
            }

            if (isset($team)) {

                $result = $this->teamRepository->destroy($this->uuid);

                if ($result) {

                    event(new DeleteEvent($team));
                }

                return $team;

            } else {
                return [];
            }

        }catch (Exception $exception){

            throw new Exception($exception->getMessage());

        }
    }
}
