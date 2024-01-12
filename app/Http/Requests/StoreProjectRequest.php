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
            'project_type' => [
                'required',
                'string',
                Rule::in([
                    Project::STATUS_FIXED,
                    Project::STATUS_HOURLY,
                ]),
            ],
            'status' => [
                'required',
                'string',
                Rule::in([
                    Project::STATUS_OPEN,
                    Project::STATUS_CLOSE,
                    Project::STATUS_CANCEL,
                ]),
            ],
        ];
    }
}
