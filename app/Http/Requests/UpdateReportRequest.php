<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Report;

class UpdateReportRequest extends FormRequest
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
            'project_id' => 'required|integer|max:250',
            'user_id' => 'required|integer|max:250',
            'working_hours' => 'required|integer|max:250',
            'price' => 'required|integer|max:250',
            'total' => 'required|between:0,99999999',
            'date' => 'required|date',
        ];
    }
}
