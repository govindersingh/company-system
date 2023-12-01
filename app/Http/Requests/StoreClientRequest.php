<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Client;

class StoreClientRequest extends FormRequest
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
            'name' => 'required|string|max:250',
            'email' => 'required|string|max:250',
            'phone' => 'nullable|string|max:250',
            'platform' => [
                'required',
                'string',
                Rule::in([
                    Client::STATUS_UPWORK,
                    Client::STATUS_FIVER,
                    Client::STATUS_SLACK,
                    Client::STATUS_WHATSAPP,
                    Client::STATUS_SKYPE,
                    Client::STATUS_OTHER,
                ]),
            ],
            'description' => 'nullable|string'
        ];
    }
}