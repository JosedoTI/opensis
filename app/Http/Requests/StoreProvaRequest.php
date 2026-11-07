<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProvaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('prova')?->id;

        return [
            'instituicao_id'            => 'required|exists:instituicoes,id',
            'ano'                       => 'required|integer|min:2016|max:2030',
            'etapa'                     => 'required|string|max:50',
            'nome'                      => 'required|string|max:200',
            'tipo'                      => 'required|in:objetiva,redacao,mista',
            'total_questoes'            => 'nullable|integer|min:1|max:200',
            'questoes_individualizadas' => 'boolean',
            'gabarito_disponivel'       => 'boolean',
            'resolucao_disponivel'      => 'boolean',
            'ativa'                     => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'instituicao_id.required' => 'Selecione uma instituição.',
            'ano.required'            => 'O ano é obrigatório.',
            'ano.min'                 => 'O ano mínimo é 2016.',
            'etapa.required'          => 'A etapa é obrigatória.',
            'nome.required'           => 'O nome da prova é obrigatório.',
        ];
    }
}
