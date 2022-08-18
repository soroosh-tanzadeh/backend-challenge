<?php

namespace App\Service;

use App\Exceptions\NotFoundException;
use App\Models\ChargeCode;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Charge wallet using charge code
     *
     * @var wallet_id Id of the wallet
     * @var chargeCode
     *
     * @return void
     */
    public function chargeWallet($wallet_id, $chargeCode): void
    {
        DB::beginTransaction();
        try {
            $wallet = Wallet::query()->lockForUpdate()->findOr(
                $wallet_id,
                ["*"],
                fn () => throw new NotFoundException("Wallet Not Found")
            );
            $chargeCodeRow = ChargeCode::query()->where("code", $chargeCode)->where("amount_left", ">", 0)->lockForUpdate()->firstOr(
                ["*"],
                fn () => throw new NotFoundException("Charge Code Not Found")
            );

            $transaction = $wallet->transactions()->create([
                "amount" => $chargeCodeRow->charge_amount,
                "action" => "charge",
                "meta" => ['charge_code' => $chargeCode]
            ]);
            $wallet->balance += $chargeCodeRow->charge_amount;
            $chargeCodeRow->amount_left -= 1;

            $chargeCodeRow->save();
            $wallet->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
