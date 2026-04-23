<?php

namespace App\Http\Requests;

use App\Models\TailorOrder;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTailorOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->canCreateOrders();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assigned_user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', User::ROLE_USER)],
            'fatora_number' => ['required', 'string', 'max:50'],
            'thobe_category' => ['required', Rule::in(array_keys(TailorOrder::categories()))],
            'quantity' => ['required', 'integer', 'min:1', 'max:500'],
            'order_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'assigned_user_id' => 'assigned user',
            'fatora_number' => 'fatora number',
            'thobe_category' => 'thobe category',
            'order_date' => 'date',
        ];
    }
}
