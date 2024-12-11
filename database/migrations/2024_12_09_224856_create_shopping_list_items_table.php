<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('shopping_list_id')
                ->constrained('shopping_lists')
                ->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('quantity')
                ->default(1);
            $table->boolean('picked_up')
                ->default(false);
            $table->integer('position')
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_list_items');
    }
};
