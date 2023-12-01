<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Project;

class StoreProjectRequest extends FormRequest
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
            'client_id' => 'required|integer|max:250',
            'name' => 'required|string|max:250',
            'description' => 'string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'budget' => 'required|between:0,99999999',
            'status' => [
                'required',
                'string',
                Rule::in([
                    Project::STATUS_PLANNED,
                    Project::STATUS_IN_PROGRESS,
                    Project::STATUS_COMPLETED,
                    Project::STATUS_CANCELLED,
                ]),
            ],
        ];
    }
}
