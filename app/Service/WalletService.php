<?php

namespace App\Service;

use App\Exceptions\ExpiredChargeCodeException;
use App\Exceptions\NotFoundException;
use App\Http\Resources\TransactionResource;
use App\Models\ChargeCode;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

            // Check if charge code is used for the wallet
            if ($chargeCodeRow->transactions()->where("wallet_id", $wallet->id)->exists()) {
                throw new HttpResponseException(response()->json(['status' => false, "data" => null, "message" => __("messages.code_used_prev")], 422));
            }


            $transaction = $wallet->transactions()->create([
                "amount" => $chargeCodeRow->charge_amount,
                "action" => "charge",
            ]);
            $transaction->chargeCode()->attach($chargeCodeRow->id);
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
        return TransactionResource::collection(
            Transaction::query()
                ->whereHas("wallet", fn ($query) => $query->whereHas("user", fn ($query) => $query->where("mobile", $mobile)))
                ->paginate()
        )
            ->response()->getData();
    }

    public function getWalletByUserMobile($mobile): Wallet
    {
        return Wallet::query()->whereHas("user", fn ($query) => $query->where("mobile", $mobile))->firstOr(fn () => throw new NotFoundException("Wallet Not Found", "user"));
    }
}
