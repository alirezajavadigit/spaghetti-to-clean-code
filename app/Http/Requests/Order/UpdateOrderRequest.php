<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'notes'            => ['nullable', 'string'],
            'due_date'         => ['nullable', 'date'],
        ];
    }
}
