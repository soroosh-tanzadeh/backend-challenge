<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChargeCode
 *
 * @property int $id
 * @property string $code
 * @property int $charge_amount
 * @property int $amount_left
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ChargeCodeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode whereAmountLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode whereChargeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChargeCode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChargeCode extends Model
{
    use HasFactory;

    protected $fillable = [
        "charge_amount",
        "code",
        "amount_left"
    ];
}
