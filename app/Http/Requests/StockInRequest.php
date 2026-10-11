<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStocker() ?? false;
    }

    public function rules(): array
    {
        return [
            'product_id'  => ['required', 'exists:products,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'qty'         => ['required', 'integer', 'min:1'],
            'unit_cost'   => ['nullable', 'numeric', 'min:0'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ];
    }
}