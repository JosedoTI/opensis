<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'prova_id'              => 'required|exists:provas,id',
            'disciplina_id'         => 'nullable|exists:disciplinas,id',
            'assunto_id'            => 'nullable|exists:assuntos,id',
            'numero'                => 'required|integer|min:1|max:200',
            'enunciado'             => 'required|string|min:10',
            'pagina_documento'      => 'nullable|integer|min:1',
            'documento_id'          => 'nullable|exists:documentos,id',
            'imagem'                => 'nullable|image|max:5120',
            'imagem_alt'            => 'nullable|string|max:500',
            'conteudo_complementar' => 'nullable|string',
            'status'                => 'in:completa,incompleta,revisao',
            'observacoes'           => 'nullable|string|max:1000',
            'alternativas'          => 'required|array|min:2|max:5',
            'alternativas.*.letra'  => 'required|in:A,B,C,D,E',
            'alternativas.*.texto'  => 'required|string|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'prova_id.required'           => 'Selecione a prova.',
            'numero.required'             => 'O número da questão é obrigatório.',
            'enunciado.required'          => 'O enunciado é obrigatório.',
            'alternativas.required'       => 'As alternativas são obrigatórias.',
            'alternativas.min'            => 'Cadastre no mínimo 2 alternativas.',
            'alternativas.*.letra.in'     => 'As letras devem ser A, B, C, D ou E.',
            'alternativas.*.texto.required' => 'O texto de cada alternativa é obrigatório.',
        ];
    }
}
