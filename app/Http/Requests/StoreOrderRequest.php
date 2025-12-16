<?php

namespace App\Http\Requests;

use App\Enums\OrderPhaseEnum;
use App\Enums\SizeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can create orders
        return auth()->check() &&
            auth()->user()->role !== \App\Enums\RoleEnum::CUSTOMER;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:users,id'],
        'meeting_id' => ['nullable', 'exists:meetings,id'],
        'current_phase' => ['required'],
        'requires_printing' => ['nullable', 'boolean'],

        'items' => ['required', 'array', 'min:1'],
        'items.*.name' => ['required', 'string', 'max:255'],
        'items.*.fabric_name' => ['nullable', 'string', 'max:255'],
        'items.*.has_printing' => ['nullable', 'boolean'],
        'items.*.description' => ['nullable', 'string'],
        'items.*.single_price' => ['required', 'numeric', 'min:0'],

        'items.*.sizes' => ['required', 'array', 'min:1'],
        'items.*.sizes.*.size_id' => ['required', 'exists:sizes,id'],
        'items.*.sizes.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'items.required' => 'At least one item is required.',
            'items.*.name.required' => 'Item name is required.',
            'items.*.single_price.required' => 'Item price is required.',
            'items.*.single_price.min' => 'Item price must be at least 1.',
            'items.*.sizes.required' => 'At least one size is required for each item.',
            'items.*.sizes.*.size_id.exists' => 'The selected size does not exist.',
            'items.*.sizes.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert string booleans to actual booleans
        $this->merge([
            'requires_printing' => filter_var($this->requires_printing, FILTER_VALIDATE_BOOLEAN),
        ]);

        // Ensure all boolean fields in items are properly cast
        if ($this->has('items')) {
            $items = $this->items;
            foreach ($items as &$item) {
                if (isset($item['has_printing'])) {
                    $item['has_printing'] = filter_var($item['has_printing'], FILTER_VALIDATE_BOOLEAN);
                }
            }
            $this->merge(['items' => $items]);
        }
    }

    /**
     * Get the validated data from the request.
     * Override to add custom data before validation.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Add created_by to validated data
        $validated['created_by'] = auth()->id();

        return $validated;
    }
}
