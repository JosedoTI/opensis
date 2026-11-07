<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDenunciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->ativo ?? false;
    }

    public function rules(): array
    {
        return [
            'motivo'    => 'required|in:incorreta,ofensiva,spam,plagio,outro',
            'descricao' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'motivo.required' => 'Selecione o motivo da denúncia.',
            'motivo.in'       => 'Motivo inválido.',
        ];
    }
}
