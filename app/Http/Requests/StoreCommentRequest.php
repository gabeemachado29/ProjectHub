<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pega a tarefa da rota e verifica se o usuário pode atualizá-la (e portanto, comentar)
        $task = $this->route('task');
        return $this->user()->can('update', $task);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|min:3'
        ];
    }
}