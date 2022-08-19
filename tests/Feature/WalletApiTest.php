<?php

namespace Tests\Feature;

use App\Models\ChargeCode;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletApiTest extends TestCase
{
    use WithFaker;

    public function test_charge_wallet()
    {
        $chargeCode = ChargeCode::factory()->create();
        $response = $this->post(route("wallet.charge"), ["mobile" => $this->faker()->e164PhoneNumber(), "code" => $chargeCode->code]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', "data" => ['transaction'], "message"]);


        $transactionAmount = $chargeCode->charge_amount;
        $response->assertJsonPath("data.transaction.amount", $transactionAmount);
    }

    public function test_get_transactions()
    {
        $chargeCode = ChargeCode::factory()->create();
        $mobile = $this->faker()->e164PhoneNumber();
        $this->post(route("wallet.charge"), ["mobile" => $mobile, "code" => $chargeCode->code])->assertStatus(200);

        $response = $this->get(route("wallet.transactions", ["mobile" => $mobile]));
        $response->assertStatus(200);

        $response->assertJsonStructure(['status', "data" => ['data'], "message"]);
        $response->assertJsonCount(1, "data.data");
    }

    /**
     *
     * @return void
     */
    public function test_get_balance()
    {
        $wallet = Wallet::factory()->for(User::factory())->create();
        $response = $this->get(route("wallet.balance", ['mobile' => $wallet->user->mobile]));
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', "data" => ['balance'], "message"]);
        $response->assertJsonPath("data.balance", $wallet->balance);
    }
}
