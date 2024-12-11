<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListRequest;
use App\Services\ShoppingListService;
use Illuminate\Http\JsonResponse;

class ShoppingListController extends Controller
{
    protected ShoppingListService $shoppingListService;

    public function __construct(ShoppingListService $shoppingListService)
    {
        $this->shoppingListService = $shoppingListService;
    }

    public function store(ShoppingListRequest $request): JsonResponse
    {
        try {
            $newList = $this->shoppingListService->createShoppingList($request->validated());
            return response()->json($newList, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create list.'], 500);
        }
    }

    public function update(ShoppingListRequest $request, $id): JsonResponse
    {
        try {
            $updatedList = $this->shoppingListService->updateShoppingList($id, $request->validated());

            if ($updatedList) {
                return response()->json($updatedList, 200);
            }

            return response()->json(['error' => 'List not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update list.'], 500);
        }
    }

    public function getAllLists(): JsonResponse
    {
        try {
            $lists = $this->shoppingListService->getAllShoppingLists();
            return response()->json($lists, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch lists.'], 500);
        }
    }


    public function destroy(int $id): JsonResponse
    {
        try {
            $isDeleted = $this->shoppingListService->deleteShoppingList($id);

            if ($isDeleted) {
                return response()->json(['message' => 'The list has been deleted.'], 200);
            }

            return response()->json(['error' => 'List not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete list.'], 500);
        }
    }


    public function getListWithItems(int $listId): JsonResponse
    {
        try {
            $list = $this->shoppingListService->getShoppingListWithItems($listId);

            if (!$list) {
                return response()->json(['error' => 'List not found.'], 404);
            }

            return response()->json([
                'id' => $list->id,
                'name' => $list->name,
                'budget' => (float) $list->budget,
                'items' => $list->items,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch list details.'], 500);
        }
    }
}
