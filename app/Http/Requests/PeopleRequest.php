<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeopleRequest extends FormRequest
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

        $peopleId = $this->route('id');

        return [
            'name' => ['required'],
            'birth' => ['required'],
            'cpf' => ['required', "unique:people,cpf,{$peopleId}"],
            'sex' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Este campo é obrigatório',
            'cpf.unique' => 'CPF já cadastrado.'
        ];
    }
}
