<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListItemRequest;
use App\Services\ShoppingListItemService;
use Illuminate\Http\JsonResponse;

class ShoppingListItemController extends Controller
{
    protected ShoppingListItemService $shoppingListItemService;

    public function __construct(ShoppingListItemService $shoppingListItemService)
    {
        $this->shoppingListItemService = $shoppingListItemService;
    }

    public function index(): JsonResponse
    {
        try {
            $items = $this->shoppingListItemService->getAllItems();
            return response()->json($items, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch items.'], 500);
        }
    }

    public function store(ShoppingListItemRequest $request): JsonResponse
    {
        try {
            $item = $this->shoppingListItemService->createItem($request->validated());
            return response()->json($item, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create item.'], 500);
        }
    }

    public function togglePickedUp($id): JsonResponse
    {
        try {
            $item = $this->shoppingListItemService->togglePickedUp($id);

            if ($item) {
                return response()->json(['success' => true, 'item' => $item], 200);
            }

            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to toggle picked up status.'], 500);
        }
    }

    public function update(ShoppingListItemRequest $request, $id): JsonResponse
    {
        try {
            $item = $this->shoppingListItemService->updateItem($id, $request->validated());

            if ($item) {
                return response()->json($item, 200);
            }

            return response()->json(['error' => 'Item not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update item.'], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $isDeleted = $this->shoppingListItemService->deleteItem($id);

            if ($isDeleted) {
                return response()->json(['message' => 'Item removed successfully.'], 200);
            }

            return response()->json(['error' => 'Item not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete item.'], 500);
        }
    }

    // not used did not have time
//    public function reorderItems(ShoppingListItemRequest $request): JsonResponse
//    {
//        $validated = $request->validate([
//            'items' => 'required|array',
//            'items.*.id' => 'required|exists:shopping_list_items,id',
//            'items.*.position' => 'required|integer|min:1',
//        ]);
//
//        try {
//            $this->shoppingListItemService->reorderItems($validated['items']);
//            return response()->json(['success' => true], 200);
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'Failed to reorder items.'], 500);
//        }
//    }
}
