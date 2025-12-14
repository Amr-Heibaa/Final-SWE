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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fabric_name')->nullable();
            $table->boolean('has_printing')->default(false);
            $table->text('description')->nullable();
            $table->integer('single_price'); // int price
            $table->timestamps();


                 // FIXED: Use uuid() instead of foreignId() for UUID reference
            $table->uuid('order_id');

             // Add foreign key constraint manually
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
