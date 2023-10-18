<?php

namespace App\Http\Controllers\V1\Desk;

use App\Http\Requests\V1\Desk\Account\StoreAccountRequest;
use App\Http\Requests\V1\Desk\Account\UpdateAccountRequest;
use App\Http\Resources\V1\DeskAccount\IndexResource;
use App\Http\Resources\V1\DeskAccount\SingleResource;
use App\Jobs\V1\DeskAccount\DeleteDeskAccountJob;
use App\Jobs\V1\DeskAccount\GetDeskAccountJob;
use App\Jobs\V1\DeskAccount\ReadDeskAccountJob;
use App\Jobs\V1\DeskAccount\StoreDeskAccountJob;
use App\Jobs\V1\DeskAccount\UpdateDeskAccountJob;
use Briofy\RestLaravel\Http\Controllers\RestController;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends RestController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(Request $request) : JsonResponse
    {
        $attributes = $request->only([ 'title', 'type', 'status' ]);
        return $this->respond(IndexResource::collection(dispatch_sync(new GetDeskAccountJob($attributes))));
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function show(string $uuid) : JsonResponse
    {
        return $this->respond(SingleResource::make(dispatch_sync(new ReadDeskAccountJob($uuid))));
    }

    /**
     * @param StoreAccountRequest $request
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function store(StoreAccountRequest $request) : JsonResponse
    {
        $attributes = $request->validated();
        return $this->respond(SingleResource::make(dispatch_sync(new StoreDeskAccountJob($attributes))));
    }

    /**
     * @param UpdateAccountRequest $request
     * @param string                   $uuid
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function update(UpdateAccountRequest $request, string $uuid) : JsonResponse
    {
        $result = dispatch_sync(new UpdateDeskAccountJob($uuid, $request->validated()));
        return $this->respond(SingleResource::make($result));
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function destroy(string $uuid) : JsonResponse
    {
        return $this->respondEntityRemoved(dispatch_sync(new DeleteDeskAccountJob($uuid)));
    }
}
