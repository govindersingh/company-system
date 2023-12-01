<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Billing;

class UpdateBillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => 'required|integer|max:250',
            'amount' => 'required|between:0,99999999',
            'date' => 'required|date',
            'status' => [
                'required',
                'string',
                Rule::in([
                    Billing::STATUS_UNPAID,
                    Billing::STATUS_PAID,
                    Billing::STATUS_OVERDUE,
                ]),
            ],
        ];
    }
}
