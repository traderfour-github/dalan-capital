<?php

namespace App\Http\Resources\V1\DeskAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'                  => $this->id,
            'desk_id'             => $this->desk_id,
            'trading_account_id'  => $this->trading_account_id,
            'risk_management_id'  => $this->risk_management_id,
            'money_management_id' => $this->money_management_id,
            'title'               => $this->title,
            'is_public'           => $this->is_public,
            'type'                => $this->type,
            'status'              => $this->status
        ];
    }
}
