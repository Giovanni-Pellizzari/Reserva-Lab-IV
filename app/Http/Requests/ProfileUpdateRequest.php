<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir la autorización (puedes personalizarlo si es necesario)
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'], // Asegúrate de que 'name' esté validado
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
        ];
    }
}
