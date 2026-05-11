<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'      => ['required', 'integer', 'exists:customers,id'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'products'         => ['required', 'array', 'min:1'],
            'products.*'       => ['integer', 'min:1'],
            'notes'            => ['nullable', 'string'],
            'due_date'         => ['nullable', 'date'],
            'attachment'       => ['nullable', 'file', 'max:5120'],
        ];
    }
}
