<?php

namespace Tests\Unit;

use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Services\ShoppingListItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListItemServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ShoppingListItemService $service;
    protected ShoppingList $shoppingList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ShoppingListItemService::class);

        $this->shoppingList = ShoppingList::factory()->create();
    }

    /** @test */
    public function userCanCreateAnItem()
    {
        $data = [
            'name' => 'Milk',
            'price' => 2.5,
            'picked_up' => false,
            'shopping_list_id' => $this->shoppingList->id,
            'quantity' => 2,
        ];

        $item = $this->service->createItem($data);

        $this->assertInstanceOf(ShoppingListItem::class, $item);
        $this->assertDatabaseHas('shopping_list_items', [
            'name' => 'Milk',
            'price' => 2.5,
            'quantity' => 2,
            'shopping_list_id' => $this->shoppingList->id,
        ]);
    }

    /** @test */
    public function userCanTogglePickedUpStatus()
    {
        $item = ShoppingListItem::factory()->create([
            'shopping_list_id' => $this->shoppingList->id,
            'picked_up' => false,
        ]);

        $updatedItem = $this->service->togglePickedUp($item->id);

        $this->assertTrue($updatedItem->picked_up);
        $this->assertDatabaseHas('shopping_list_items', [
            'id' => $item->id,
            'picked_up' => true,
        ]);
    }

    /** @test */
    public function userCanUpdateAnItem()
    {
        $item = ShoppingListItem::factory()->create([
            'shopping_list_id' => $this->shoppingList->id,
        ]);

        $updatedData = [
            'name' => 'Spoiled Milk',
            'price' => 3.0,
            'quantity' => 5,
        ];

        $updatedItem = $this->service->updateItem($item->id, $updatedData);

        $this->assertEquals('Spoiled Milk', $updatedItem->name);
        $this->assertEquals(3.0, $updatedItem->price);
        $this->assertEquals(5, $updatedItem->quantity);
        $this->assertDatabaseHas('shopping_list_items', [
            'id' => $item->id,
            'name' => 'Spoiled Milk',
            'price' => 3.0,
            'quantity' => 5,
        ]);
    }

    /** @test */
    public function userCanDeleteAnItem()
    {
        $item = ShoppingListItem::factory()->create([
            'shopping_list_id' => $this->shoppingList->id,
        ]);

        $this->service->deleteItem($item->id);

        $this->assertDatabaseMissing('shopping_list_items', [
            'id' => $item->id,
        ]);
    }

    /** @test */
    public function canRetrieveAllItems()
    {
        ShoppingListItem::factory()->count(3)->create([
            'shopping_list_id' => $this->shoppingList->id,
        ]);

        $items = $this->service->getAllItems();

        $this->assertCount(3, $items);
        $this->assertInstanceOf(ShoppingListItem::class, $items->first());
    }
}
