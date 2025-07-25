<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class CreateSecurePaymentToken extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'RESPONSECODE' => 'required|string',
            'RESPONSETEXT' => 'required|string',
            'MERCHANTREF' => 'required|string',
            'DATETIME' => 'required',
            'HASH' => 'required|string',
            'CARDTYPE' => 'required|string',
            'MASKEDCARDNUMBER' => 'required|string',
            'CARDEXPIRY' => 'required|numeric',
            'CARDHOLDERNAME' => 'required',
            'CARDREFERENCE' => 'required|numeric',
        ];
    }
}