<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRespostaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->ativo ?? false;
    }

    public function rules(): array
    {
        return [
            'conteudo'          => 'required|string|min:20|max:5000',
            'resposta_indicada' => 'nullable|in:A,B,C,D,E',
        ];
    }

    public function messages(): array
    {
        return [
            'conteudo.required' => 'O conteúdo da resposta é obrigatório.',
            'conteudo.min'      => 'A resposta deve ter no mínimo 20 caracteres.',
            'conteudo.max'      => 'A resposta não pode ter mais de 5000 caracteres.',
        ];
    }
}
