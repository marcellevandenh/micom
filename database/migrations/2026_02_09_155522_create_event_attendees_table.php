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
        Schema::create('basket_item_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('basket_item_id')->constrained()->cascadeOnDelete();

            // position/index so you can keep Adult #1, Adult #2 stable
            $table->unsignedInteger('seat_number');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('dietary_requirements')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['basket_item_id', 'seat_number']);
        });

        Schema::create('event_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('basket_item_id')->nullable();
            $table->foreignId('basket_item_attendees_id')
                ->nullable()
                ->constrained('basket_item_attendees')
                ->nullOnDelete();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('dietary')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendees');
        Schema::dropIfExists('basket_item_attendees');

    }
};
