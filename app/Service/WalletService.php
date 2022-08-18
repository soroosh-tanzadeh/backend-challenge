<?php

namespace App\Service;

use App\Exceptions\ExpiredChargeCodeException;
use App\Exceptions\NotFoundException;
use App\Models\ChargeCode;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Charge wallet using charge code
     *
     * @param $wallet_id Id of the wallet
     * @param $chargeCode
     *
     * @return Transaction | null
     */
    public function chargeWallet($wallet_id, $chargeCode)
    {
        DB::beginTransaction();
        try {
            $wallet = $wallet_id instanceof Wallet ? $wallet_id : Wallet::query()->lockForUpdate()->findOr(
                $wallet_id,
                ["*"],
                fn () => throw new NotFoundException("Wallet Not Found", "wallet")
            );
            $chargeCodeRow = ChargeCode::query()->where("code", $chargeCode)->where("amount_left", ">", 0)->lockForUpdate()->firstOr(
                ["*"],
                fn () => throw new ExpiredChargeCodeException("Charge Code Not Found", "chargeCode")
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

            return $transaction;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getTransactions($mobile)
    {
        return Transaction::query()->whereHas("wallet", fn ($query) => $query->whereHas("user", fn ($query) => $query->where("mobile", $mobile)))->paginate();
    }

    public function getWalletByUserMobile($mobile): Wallet
    {
        return Wallet::query()->whereHas("user", fn ($query) => $query->where("mobile", $mobile))->firstOr(fn () => throw new NotFoundException("Wallet Not Found", "wallet"));
    }
}
