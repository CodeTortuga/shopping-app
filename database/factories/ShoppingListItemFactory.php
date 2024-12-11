<?php

namespace Database\Factories;

use App\Models\ShoppingListItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingListItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShoppingListItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'shopping_list_id' => null,
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0.5, 100),
            'quantity' => $this->faker->numberBetween(1, 10),
            'picked_up' => 0,
            'position' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
