<?php

namespace App\Services;

use App\Models\ShoppingList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ShoppingListService
{
    public function createShoppingList(array $data): ShoppingList
    {
        $newList = new ShoppingList();
        $newList->uuid = Str::uuid();
        $newList->name = $data['name'];
        $newList->budget = $data['budget'];
        $newList->save();

        return $newList;
    }

    public function getAllShoppingLists(): Collection
    {
        return ShoppingList::all();
    }

    public function getShoppingListWithItems(int $listId)
    {
        return ShoppingList::with('items')->find($listId);
    }
}
