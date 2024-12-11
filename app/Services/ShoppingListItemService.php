<?php

namespace App\Services;

use App\Models\ShoppingListItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ShoppingListItemService
{
    public function getAllItems(): Collection
    {
        return ShoppingListItem::orderBy('created_at', 'desc')->get();
    }

    public function createItem(array $data): ShoppingListItem
    {
        return ShoppingListItem::create([
            'uuid' => Str::uuid()->toString(),
            'name' => $data['name'],
            'price' => $data['price'],
            'picked_up' => $data['picked_up'] ?? false,
            'shopping_list_id' => $data['shopping_list_id'],
            'quantity' => $data['quantity'],
        ]);
    }

    public function togglePickedUp(int $id): ?ShoppingListItem
    {
        $item = ShoppingListItem::find($id);

        if ($item) {
            $item->picked_up = !$item->picked_up;
            $item->save();
        }

        return $item;
    }

    public function updateItem(int $id, array $data): ?ShoppingListItem
    {
        $item = ShoppingListItem::find($id);

        if ($item) {
            $item->update($data);
        }

        return $item;
    }

    public function deleteItem(int $id): bool
    {
        $item = ShoppingListItem::find($id);

        if ($item) {
            $item->delete();
            return true;
        }

        return false;
    }

//    for the reordering but did not have time
//    public function reorderItems(array $items): bool
//    {
//        foreach ($items as $item) {
//            ShoppingListItem::where('id', $item['id'])->update(['position' => $item['position']]);
//        }
//
//        return true;
//    }
}
