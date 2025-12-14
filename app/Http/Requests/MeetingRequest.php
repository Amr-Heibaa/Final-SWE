<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],

            'phone' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:20',
                'regex:/^\+?[0-9]{9,20}$/', 
            ],

            'brand_name' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],

            'scheduled_date' => [
                $isUpdate ? 'sometimes' : 'required',
                'date',
                'after:now',
            ],

            // 'status' => [
            //     'nullable',
            //     Rule::in(['pending', 'completed', 'cancelled']),
            // ],
        ];
    }
}
