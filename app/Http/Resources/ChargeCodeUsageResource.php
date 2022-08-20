<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChargeCodeUsageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "trans_id" => $this->id,
            "amount" => $this->amount,
            "code" => $this->chargeCode->get(0)->charge_amount,
            "user" => $this->wallet->user,
            "time" => $this->created_at
        ];
    }
}
