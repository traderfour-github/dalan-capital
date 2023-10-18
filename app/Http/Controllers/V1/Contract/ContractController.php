<?php

namespace App\Http\Controllers\V1\Contract;

use App\Http\Requests\V1\Contract\UpdateContractRequest;
use Briofy\RestLaravel\Http\Controllers\RestController;
use App\Http\Requests\V1\Contract\CreateContractRequest;
use App\Http\Resources\V1\Contract\SingleResource;
use App\Http\Resources\V1\Contract\IndexResource;
use App\Jobs\V1\Contract\DeleteContractJob;
use App\Jobs\V1\Contract\UpdateContractJob;
use App\Jobs\V1\Contract\CreateContractJob;
use App\Jobs\V1\Contract\ReadContractJob;
use App\Jobs\V1\Contract\GetContractJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractController extends RestController
{
    public function __construct() {}

    public function index(Request $request) : JsonResponse
    {
        $data = $request->only([
            'team_id', 'team_trader_id', 'desk_id', 'desk_account_id', 'number', 'title',
            'description', 'share', 'start_balance', 'current_balance', 'currency', 'profits',
            'harvestable', 'harvested', 'scale_up_amount', 'scale_up_times', 'scaled_up_at',
            'target', 'is_public', 'status', 'synced_at'
        ]);

        return $this->respond(
            IndexResource::collection(dispatch_sync(new GetContractJob($data)))
        );
    }

    public function show($uuid): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new ReadContractJob($uuid)))
        );
    }

    public function store(CreateContractRequest $request): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new CreateContractJob($request->validated())))
        );
    }

    public function update(UpdateContractRequest $request, $uuid): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new UpdateContractJob($uuid, $request->validated())))
        );
    }

    public function destroy($uuid): JsonResponse
    {
        return $this->respondEntityRemoved(dispatch_sync(new DeleteContractJob($uuid)));
    }
}
