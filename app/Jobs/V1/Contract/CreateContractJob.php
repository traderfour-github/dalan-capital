<?php

namespace App\Jobs\V1\Contract;

use App\Repositories\V1\Contract\ContractRepository;
use App\Events\V1\Contract\ContractCreateEvent;
use App\Models\DalanCapital\V1\Contract;
use App\Jobs\SyncJob;
use Exception;

class CreateContractJob extends SyncJob
{
    private $repository;

    /**
     * @param  array  $data
     */
    public function __construct(
        private array $data
    ) {
        $this->repository = new ContractRepository();
    }

    /**
     * @param  array  $data
     *
     * @return Contract
     * @throws Exception
     */
    public function handle(): Contract
    {
        try {

            $contract = $this->repository->transactional(fn() => $this->repository->store($this->data));

            if(isset($contract)){
                ContractCreateEvent::dispatch($contract);
                return $contract ;
            } else {
                return [];
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
