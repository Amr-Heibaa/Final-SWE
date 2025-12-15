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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //date
                $table->integer('total_price');
            $table->boolean('requires_printing')->default(false);
            $table->enum('current_phase',
            ['cutting', 'printing', 'sewing', 'packaging', 'delivery', 'completed'])->default('cutting');


            // Creator
            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('restrict');
            //customer
            $table->foreignId('customer_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->string('customer_name');          // NEW
            $table->string('brand_name')->nullable(); // NEW
            //meeting
        $table->foreignId('meeting_id')
        ->nullable()
        ->constrained('meetings')
        ->onDelete('set null');


            $table->index('customer_id');
            $table->index('created_by');
            $table->index('current_phase');
            $table->index('requires_printing');


            $table->timestamps();




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
