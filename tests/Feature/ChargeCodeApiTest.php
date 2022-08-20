<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Service\WalletService;
use App\Models\Wallet;
use App\Models\User;
use App\Models\ChargeCode;

class ChargeCodeApiTest extends TestCase
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
    public function test_get_charge_codes()
    {
        $prevCount = ChargeCode::query()->count();

        // Create Charge Code using factory
        $chargeCode = ChargeCode::factory()->create();

        $response = $this->get(route("charge-codes.index"));
        $response->assertOk();
        $response->assertJsonStructure(["status", "data", "message"]);
        $response->assertJsonPath("data.meta.total", $prevCount + 1);
    }

    public function test_create_charge_code()
    {
        $response = $this->post(route("charge-codes.store"), ['max_usage' => mt_rand(1000, 10000), "charge_amount" => mt_rand(1500, 5000000)]);
        $response->assertOk();
        $response->assertJsonStructure(["status", "data" => [
            "charge_amount",
            "amount_left",
            "max_usage",
            "code",
            "is_usable"
        ], "message"]);
        $response->assertJsonPath("data.is_usable", true);

        $chargeCode = ChargeCode::factory()->create();
        $response = $this->post(route("charge-codes.store"), ['max_usage' => mt_rand(1000, 10000), "charge_amount" => mt_rand(1500, 5000000), "code" => "$chargeCode->code"]);
        $response->assertStatus(422);
        $response->assertJsonPath("message", __("validation.unique", ['attribute' => "code"]));
    }

    public function test_charge_code_usage()
    {
        $wallet = Wallet::factory()->for(User::factory(), "user")->createOne();

        $chargeCode = ChargeCode::factory()->create();
        $transaction = $this->walletService->chargeWallet($wallet->id, $chargeCode->code);

        $response = $this->get(route("charge-codes.show", ['charge_code' => $chargeCode->code]));
        $response->assertOk();

        $response->assertJsonStructure(["status", "data" => [
            "data"
        ], "message"]);
        $response->assertJsonCount(1, "data.data");

        $response->assertJsonPath("data.data.0.trans_id", $transaction->id);
    }
}
