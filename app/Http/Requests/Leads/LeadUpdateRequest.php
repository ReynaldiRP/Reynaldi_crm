<?php

namespace App\Http\Requests\Leads;

use Illuminate\Foundation\Http\FormRequest;

class LeadUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:20',
            'email' => 'sometimes|string|email|max:255|unique:users,email',
            'address' => 'sometimes|string|max:255',
            'needs' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|in:new,contacted,qualified,lost,converted',
            'converted_at' => 'nullable|date',
            'converted_to_customer_id' => 'nullable|exists:customers,id',
        ];
    }
}
