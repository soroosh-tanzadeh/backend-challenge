<?php

namespace Tests\Unit;

use App\Models\ChargeCode;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Service\WalletService;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{

    private WalletService $walletService;

    public function setUp(): void
    {
        parent::setUp();
        $this->walletService = app()->make(WalletService::class);
    }

    /**
     * @return void
     */
    public function test_charge_wallet_exceptions()
    {
        $this->expectExceptionMessage("Wallet Not Found");
        $this->walletService->chargeWallet("", ChargeCode::factory()->create()->code);


        $this->expectExceptionMessage("Charge Code");
        $this->walletService->chargeWallet(Wallet::factory()->has(User::factory(), "user")->create()->id, "");
    }

    public function test_charge_wallet_using_code()
    {
        $wallet = Wallet::factory()->for(User::factory(), "user")->createOne();

        $chargeCode = ChargeCode::factory()->create();
        $this->walletService->chargeWallet($wallet->id, $chargeCode->code);

        $this->assertEquals($chargeCode->amount, $wallet->balance, "Wallet Charge Not Working");
        $this->assertTrue(Transaction::query()->whereWalletId($wallet->id)->whereHas("chargeCode", fn ($query) => $query->whereCode($chargeCode->code))->exists(), "Transaction not created");
    }
}
