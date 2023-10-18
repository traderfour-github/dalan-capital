<?php

namespace App\Http\Resources\V1\Contract;

use App\Http\Resources\V1\Desk\SummeryResource as DeskSummeryResource;
use App\Http\Resources\V1\DeskAccount\SummeryResource as DeskAccountSummeryResource;
use App\Http\Resources\V1\Team\SummeryResource as TeamSummeryResource;
use App\Http\Resources\V1\Team\Trader\SummeryResource as TeamTradeSummeryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'uuid'             => $this->id,
            'teams'            => $this->whenLoaded('teams' , fn() => new TeamSummeryResource($this->teams)),
            'team_traders'     => $this->whenLoaded('teamTraders' , fn() => new TeamTradeSummeryResource($this->teamTraders)),
            'desks'            => $this->whenLoaded('desks' , fn() => new DeskSummeryResource($this->desks)),
            'desk_accounts'    => $this->whenLoaded('deskAccounts' , fn() => new DeskAccountSummeryResource($this->deskAccounts)),
            'title'            => $this->title,
            'description'      => $this->description,
            'number'           => $this->number,
            'share'            => $this->share,
            'start_balance'    => $this->start_balance,
            'current_balance'  => $this->current_balance,
            'currency'         => $this->currency,
            'profits'           => $this->profits,
            'harvestable'      => $this->harvestable,
            'harvested'        => $this->harvested,
            'scale_up_amount'  => $this->scale_up_amount,
            'scaled_up_times'  => $this->scaled_up_times,
            'scaled_up_at'     => $this->scaled_up_at,
            'target'           => $this->target,
            'synced_at'        => $this->synced_at,
            'status'           => $this->status,
        ];
    }
}
