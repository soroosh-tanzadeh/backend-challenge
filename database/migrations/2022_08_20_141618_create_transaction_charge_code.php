<?php

use App\Models\ChargeCode;
use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_charge_code', function (Blueprint $table) {
            $table->foreignIdFor(ChargeCode::class)->constrained()->restrictOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(Transaction::class)->unique()->constrained()->restrictOnUpdate()->restrictOnDelete();

            $table->primary(['charge_code_id', "transaction_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_charge_code');
    }
};
