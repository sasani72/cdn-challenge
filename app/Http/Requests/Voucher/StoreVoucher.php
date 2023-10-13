<?php

namespace App\Http\Requests\Voucher;

use App\Enums\VoucherTypes;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreVoucher extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|min:3',
            'code'        => 'required|string|min:3',
            'type'        => ['required', new Enum(VoucherTypes::class)],
            'amount'      => 'required|numeric',
            'max_uses'    => 'required|numeric|min:1',
            'starts_at'   => 'required|date|after:now|before:expires_at',
            'expires_at'  => 'required|date|after:now|after:starts_at',
            'description' => 'nullable|string',
        ];
    }

    /**
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => "Validation Error",
            'data' => $validator->errors()
        ]));
    }
}
