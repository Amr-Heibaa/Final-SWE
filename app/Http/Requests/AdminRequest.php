<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only Super Admin can create/edit admins
        return $this->user()->role === 'super_admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id ?? null),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', Rule::in(['admin', 'admin_manager'])],
        ];
    }
}
