<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProtocolRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'description' => ['required'],
            'term' => ['required', 'date'],
            'people_id' => ['required'],
        ];
    }
    
public function messages(): array
    {
        return [
            'required' => 'Este campo é obrigatório',
            'date' => 'Deve ser uma data válida',
        ];
    }
}
