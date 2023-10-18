<?php

namespace App\Http\Controllers\V1\Desk;

use Briofy\RestLaravel\Http\Controllers\RestController;
use App\Http\Requests\V1\Desk\CreateDeskRequest;
use App\Http\Requests\V1\Desk\UpdateDeskRequest;
use App\Http\Resources\V1\Desk\IndexResource;
use App\Http\Resources\V1\Desk\SingleResource;
use App\Jobs\V1\Desk\DeleteDeskJob;
use App\Jobs\V1\Desk\UpdateDeskJob;
use Illuminate\Http\JsonResponse;
use App\Jobs\V1\Desk\CreateDeskJob;
use App\Jobs\V1\Desk\ReadDeskJob;
use App\Jobs\V1\Desk\GetDeskJob;
use Illuminate\Http\Request;

class DeskController extends RestController
{
    private const USERID = 'id';

    public function __construct() {}

    public function index(Request $request) : JsonResponse
    {
        $data = $request->only([
            'title', 'description', 'content', 'logo', 'cover',
            'aum_amount', 'aum_currency', 'is_public', 'status', 'synced_at'
        ]);

        return $this->respond(
            IndexResource::collection(dispatch_sync(new GetDeskJob($data)))
        );
    }

    public function show($uuid): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new ReadDeskJob($uuid)))
        );
    }

    public function store(CreateDeskRequest $request): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new CreateDeskJob($request->validated() , $request->user()[self::USERID])))
        );
    }

    public function update(UpdateDeskRequest $request, $uuid): JsonResponse
    {
        return $this->respond(
            SingleResource::make(dispatch_sync(new UpdateDeskJob($uuid, $request->validated())))
        );
    }

    public function destroy($uuid): JsonResponse
    {
        return $this->respondEntityRemoved(dispatch_sync(new DeleteDeskJob($uuid)));
    }
}
