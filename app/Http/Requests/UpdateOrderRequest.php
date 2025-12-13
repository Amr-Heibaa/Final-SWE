<?php

namespace App\Http\Requests;

use App\Enums\OrderPhaseEnum;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
             // Get the order from route parameter
        $order = $this->route('order');

        // Only admins can update orders
        if (auth()->user()->role === \App\Enums\RoleEnum::CUSTOMER) {
            return false;
        }

        // Only allow if order is in editable phases
    return in_array($order->current_phase, [
        \App\Enums\OrderPhaseEnum::PENDING->value,
        \App\Enums\OrderPhaseEnum::CUTTING->value
    ]);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $order = $this->route('order');
        return [
            'customer_id' => [
                'required',
                'exists:users,id',
                Rule::exists('users', 'id')->where('role', 'customer')
            ],
            'meeting_id' => [
                'nullable',
                'exists:meetings,id',
            ],
            'requires_printing' => 'boolean',
            'current_phase' => [
                'required',
                // Get valid phases based on requires_printing
                Rule::in(OrderPhaseEnum::forDropdown($this->requires_printing ?? $order->requires_printing))
            ],

        ];
    }
    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'current_phase.in' => 'The selected phase is not valid for this order.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'requires_printing' => filter_var($this->requires_printing, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'customer_id' => 'customer',
            'meeting_id' => 'meeting',
            'current_phase' => 'phase',
        ];
    }
}

