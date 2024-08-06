<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStopRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'position_longitude' => 'required|string|max:255',
            'position_latitude' => 'required|string|max:255',
            'image' => 'nullable|image|max:150',
            'notes' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'day_id' => 'required|exists:days,id'
        ];
    }
}
