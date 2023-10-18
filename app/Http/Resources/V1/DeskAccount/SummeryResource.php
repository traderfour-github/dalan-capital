<?php

namespace App\Http\Resources\V1\DeskAccount;

use Illuminate\Http\Resources\Json\JsonResource;

class SummeryResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'                  => $this->id,
            'title'               => $this->title,
            'desk_id'             => $this->desk_id,
            'trading_account_id'  => $this->trading_account_id,
            'risk_management_id'  => $this->risk_management_id,
            'money_management_id' => $this->money_management_id,
        ];
    }
}
