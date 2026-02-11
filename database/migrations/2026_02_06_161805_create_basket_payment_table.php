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
        Schema::create('basket_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('basket_id')->cascadeOnDelete();
            $table->string('payment_reference');
            $table->decimal('amount', 12, 2);
            $table->dateTime('payment_date')->nullable();
            $table->string('description')->nullable();
            $table->index(['basket_id']);
            $table->index(['payment_reference']);
            $table->string('payment_method')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basket_payment');
    }
};
