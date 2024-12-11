<?php

namespace Tests\Unit;

use App\Models\ShoppingList;
use App\Models\ShoppingListItem;
use App\Services\ShoppingListService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ShoppingListService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ShoppingListService::class);
    }

    /** @test */
    public function userCanCreateShoppingList()
    {
        $data = [
            'name' => 'Groceries',
            'budget' => 100.0,
        ];

        $list = $this->service->createShoppingList($data);

        $this->assertInstanceOf(ShoppingList::class, $list);
        $this->assertDatabaseHas('shopping_lists', [
            'name' => 'Groceries',
            'budget' => 100.0,
        ]);
    }

    /** @test */
    public function canRetrieveAllShoppingLists()
    {
        ShoppingList::factory()->count(3)->create();

        $lists = $this->service->getAllShoppingLists();

        $this->assertCount(3, $lists);
        $this->assertInstanceOf(ShoppingList::class, $lists->first());
    }

    /** @test */
    public function userCanGetShoppingListWithItems()
    {
        $list = ShoppingList::factory()->create();
        ShoppingListItem::factory()->count(2)->create(['shopping_list_id' => $list->id]);

        $listWithItems = $this->service->getShoppingListWithItems($list->id);

        $this->assertInstanceOf(ShoppingList::class, $listWithItems);
        $this->assertCount(2, $listWithItems->items);
    }

    /** @test */
    public function userCannotGetNonExistentShoppingListWithItems()
    {
        $listWithItems = $this->service->getShoppingListWithItems(999);

        $this->assertNull($listWithItems);
    }
}
