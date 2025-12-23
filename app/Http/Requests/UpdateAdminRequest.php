<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) return false;

        // role كـ string
        $role = $user->role instanceof RoleEnum
            ? $user->role->value
            : (string) $user->role;

        return $role === RoleEnum::SUPER_ADMIN->value;
    }

    public function rules(): array
    {
        $admin = $this->route('user'); // نفس اسم route parameter

        return [
            'name'  => ['required', 'string', 'max:255'],

            // ✅ مهم جدًا ignore نفس اليوزر
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($admin?->id),
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            // ✅ password اختياري
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
