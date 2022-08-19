<?php

namespace App\Http\Requests\V1\ChargeCodes;

use App\Http\Requests\V1\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class CreateChargeCodeRequest extends BaseRequest
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
            "code" => ['nullable', "min:5", "max:20", "string", "unique:charge_codes,code"],
            "max_usage" => ['required', "numeric", "min:1"],
            "charge_amount" => ["required", "numeric", "min:1000"]
        ];
    }
}
