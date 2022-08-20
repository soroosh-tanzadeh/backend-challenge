<?php

namespace App\Service;

use App\Http\Resources\ChargeCodeResource;
use App\Http\Resources\ChargeCodeUsageResource;
use App\Models\ChargeCode;
use App\Models\Transaction;

class ChargeCodeService
{
    /**
     * @param int $chargeAmount
     * @param int $maxUsage
     * @param string $code
     */
    public function createChargeCode($chargeAmount, $maxUsage, $code = null)
    {
        if ($code == null) {
            $code = mt_rand(10000000, 99999999);
            if (ChargeCode::query()->where("code", $code)->exists()) {
                return $this->createChargeCode($chargeAmount, $maxUsage);
            }
        }

        return ChargeCode::query()->create([
            "code" => $code,
            "amount_left" => $maxUsage,
            "charge_amount" => $chargeAmount
        ]);
    }

    public function getUsageReport($code)
    {
        return ChargeCodeUsageResource::collection(
            Transaction::query()->with(['wallet', "wallet.user", "chargeCode"])->whereHas("chargeCode", fn ($query) => $query->whereCode($code))->paginate()
        )->response()->getData();
    }

    public function getChargeCodes()
    {
        return ChargeCodeResource::collection(ChargeCode::query()->latest()->paginate())->response()->getData();
    }
}
