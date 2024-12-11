<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('id') || $this->method() === 'PUT';

        return [
            'name' => ($isUpdate ? 'sometimes|' : 'required|') . 'string|max:255',
            'budget' => ($isUpdate ? 'sometimes|' : 'required|') . 'numeric|min:0',
        ];
    }
}
