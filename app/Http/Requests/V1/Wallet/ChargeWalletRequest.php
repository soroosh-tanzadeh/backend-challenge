<?php

namespace App\Http\Requests\V1\Wallet;

use App\Http\Requests\V1\BaseRequest;
use App\Rules\MobileRule;

class ChargeWalletRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "code" => ['required', "exists:charge_codes"],
            "mobile" => ['required',  new MobileRule()]
        ];
    }
}
