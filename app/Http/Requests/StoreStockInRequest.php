<?php

namespace App\Http\Requests;

<<<<<<< HEAD
=======
use Illuminate\Contracts\Validation\ValidationRule;
>>>>>>> milestone/03-inventory-shipping
use Illuminate\Foundation\Http\FormRequest;

class StoreStockInRequest extends FormRequest
{
<<<<<<< HEAD
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'reference' => ['nullable', 'max:120'],
            'note' => ['nullable', 'string'],
            'movement_at' => ['required', 'date'],
        ];
    }
}
=======
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
>>>>>>> milestone/03-inventory-shipping
