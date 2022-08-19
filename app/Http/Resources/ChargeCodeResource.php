<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargeCodeResource extends JsonResource
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
            "code" => $this->code,
            "max_usage" => $this->amount_left + Transaction::query()->where("meta->charge_code", $this->code)->count(),
            "amount_left" => $this->amount_left,
            "charge_amount" => $this->charge_amount,
            "is_usable" => $this->amount_left > 0
        ];
    }
}
