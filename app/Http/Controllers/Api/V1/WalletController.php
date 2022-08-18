<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RequireMobileOnlyRequest;
use App\Http\Requests\V1\Wallet\ChargeWalletRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Service\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    private WalletService $service;

    public function __construct(WalletService $service)
    {
        $this->service = $service;
    }

    public function charge(ChargeWalletRequest $request)
    {
        $user = User::query()->firstOrCreate(['mobile' => $request->mobile]);
        $trasnaction = $this->service->chargeWallet(Wallet::query()->firstOrCreate(['user_id' => $user->id]), $request->code);

        return $this->response($trasnaction instanceof Transaction, ["transaction" => $trasnaction]);
    }

    public function transactions(RequireMobileOnlyRequest $request)
    {
        $transactions = $this->service->getTransactions($request->mobile);
        return $this->response(true, ['transactions' => $transactions]);
    }

    public function balance(RequireMobileOnlyRequest $request)
    {
        $wallet = $this->service->getWalletByUserMobile($request->mobile);
        return $this->response(true, ['balance' => $wallet->balance]);
    }
}
