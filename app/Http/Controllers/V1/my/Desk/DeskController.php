<?php

namespace App\Http\Controllers\V1\my\Desk;

use App\Http\Requests\V1\Desk\CreateDeskRequest;
use Briofy\RestLaravel\Http\Controllers\RestController;
use App\Http\Requests\V1\Desk\UpdateDeskRequest;
use App\Http\Resources\V1\Desk\IndexResource;
use App\Http\Resources\V1\Desk\SingleResource;
use App\Jobs\V1\my\Desk\DeleteDeskJob;
use App\Jobs\V1\my\Desk\UpdateDeskJob;
use App\Jobs\V1\my\Desk\ReadDeskJob;
use App\Jobs\V1\my\Desk\GetDeskJob;
use App\Jobs\V1\my\Desk\CreateDeskJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeskController extends RestController
{
    private const USERID = 'uuid';

    public function __construct() {}

    public function index(Request $request) : JsonResponse
    {
        try{
            $data = $request->only([
                'title', 'description', 'content', 'logo', 'cover','user_id',
                'aum_amount', 'aum_currency', 'status', 'synced_at'
            ]);

            return $this->respond(
                IndexResource::collection(dispatch_sync(new GetDeskJob($request->user()[self::USERID] , $data)))
            );
        }catch (\Exception $e) {
            return $this->respondWithError($e);
        }
    }

    public function show(Request $request , $uuid): JsonResponse
    {
        try{
            return $this->respond(
                SingleResource::make(dispatch_sync(new ReadDeskJob($uuid , $request->user()[self::USERID])))
            );
        }catch (\Exception $e) {
            return $this->respondWithError($e);
        }
    }


    public function store(CreateDeskRequest $request): JsonResponse
    {
        try{
            return $this->respond(
                SingleResource::make(dispatch_sync(new CreateDeskJob($request->validated() , $request->user()[self::USERID])))
            );
        }catch (\Exception $e) {
            return $this->respondWithError($e);
        }
    }


    public function update(UpdateDeskRequest $request, $uuid): JsonResponse
    {
        try{
            return $this->respond(
                SingleResource::make(dispatch_sync(new UpdateDeskJob($uuid, $request->validated())))
            );
        }catch (\Exception $e) {
            return $this->respondWithError($e);
        }
    }


    public function destroy($uuid): JsonResponse
    {
        try{
            return $this->respondEntityRemoved(dispatch_sync(new DeleteDeskJob($uuid)));
        }catch (\Exception $e) {
            return $this->respondWithError($e);
        }
    }
}

