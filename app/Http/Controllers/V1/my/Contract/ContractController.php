<?php

namespace App\Http\Controllers\V1\my\Contract;

use App\Http\Requests\V1\Contract\UpdateContractRequest;
use Briofy\RestLaravel\Http\Controllers\RestController;
use App\Http\Requests\V1\Contract\CreateContractRequest;
use App\Http\Resources\V1\Contract\SingleResource;
use App\Http\Resources\V1\Contract\IndexResource;
use App\Jobs\V1\my\Contract\DeleteContractJob;
use App\Jobs\V1\my\Contract\UpdateContractJob;
use App\Jobs\V1\my\Contract\CreateContractJob;
use App\Jobs\V1\my\Contract\ReadContractJob;
use App\Jobs\V1\my\Contract\GetContractJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractController extends RestController
{
    private const USERID = 'id';

    public function __construct() {}

    public function index(Request $request) : JsonResponse
    {
        $data = $request->only([
            'team_id', 'team_trader_id', 'desk_id', 'desk_account_id', 'number', 'title',
            'description', 'share', 'start_balance', 'current_balance', 'currency', 'profits',
            'harvestable', 'harvested', 'scale_up_amount', 'scale_up_times', 'scaled_up_at',
            'target', 'status', 'synced_at','user_id'
        ]);

        return $this->respond(
            IndexResource::collection(dispatch_sync(new GetContractJob($request->user()[self::USERID] ,$data)))
        );
    }

    public function show(Request $request , $uuid): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new ReadContractJob($uuid , $request->user()[self::USERID])))
        );
    }

    public function store(CreateContractRequest $request): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new CreateContractJob($request->validated() , $request->user()[self::USERID])))
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
