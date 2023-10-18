<?php

namespace App\Jobs\V1\Contract;

use App\Events\V1\Contract\ContractDeleteEvent;
use App\Repositories\V1\Contract\IContractRepository;
use App\Jobs\SyncJob;
use Exception;

class DeleteContractJob extends SyncJob
{
    private IContractRepository $repository;

    /**
     * @param  string  $contractId
     */
    public function __construct(
        private string $contractId
    ) {
        $this->repository = app()->make(IContractRepository::class);
    }

    /**
     *
     * @return string
     * @throws Exception
     */
    public function handle(): string
    {
        try {

            $contract = $this->repository->destroy($this->contractId);

            if(isset($contract)){
                ContractDeleteEvent::dispatch($this->contractId);

                return $contract;
            }else{
                return [];
            }



        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
