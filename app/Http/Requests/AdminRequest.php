<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use App\Models\User;

class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        // ✅ CORRECT - compare enum objects
        return $this->user()->role === RoleEnum::SUPER_ADMIN;
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
        ];
    }
}
