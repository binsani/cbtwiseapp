<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedeemPurchaseCodeRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['code' => ['required', 'string', 'regex:/^CBT-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/']]; }
    protected function prepareForValidation(): void { $this->merge(['code' => strtoupper(trim((string) $this->code))]); }
}
