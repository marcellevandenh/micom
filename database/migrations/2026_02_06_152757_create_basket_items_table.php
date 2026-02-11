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
        Schema::create('basket_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('basket_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('event_ticket_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('quantity')->default(1);
            $table->boolean('is_vat_exempt')->default(false);
            $table->decimal('price',9,2)->default(0);
            $table->decimal('fee',7,2)->default(0);
            $table->boolean('locked')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basket_items');
    }
};
