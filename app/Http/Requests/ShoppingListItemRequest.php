<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingListItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('id') || $this->method() === 'PUT';

        return [
            'shopping_list_id' => 'required|exists:shopping_lists,id',
            'name' => ($isUpdate ? 'sometimes|' : 'required|') . 'string|max:255',
            'price' => ($isUpdate ? 'sometimes|' : 'required|') . 'numeric|min:0',
            'quantity' => ($isUpdate ? 'sometimes|' : 'required|') . 'integer|min:1',
            'picked_up' => 'sometimes|boolean',
        ];
    }
}
