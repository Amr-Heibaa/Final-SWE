<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_item_sizes', function (Blueprint $table) {
            $table->id();

             $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->onDelete('cascade');

            $table->foreignId('size_id')
                ->constrained('sizes')
                ->onDelete('restrict');

            $table->integer('quantity');

            $table->timestamps();

            $table->unique(['order_item_id', 'size_id']);

            $table->index('order_item_id');
            $table->index('size_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_sizes');
    }
};
