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

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            $table->string('address_town')->nullable();
            $table->string('address_county')->nullable();
            $table->string('address_postcode')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('venue_id')->nullable();
            $table->boolean('published')->index()->default(0);
            $table->boolean('is_bookable')->nullable();
            $table->string('short_name')->nullable();
            $table->string('alert')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('duration')->nullable();
            $table->string('prerequisites')->nullable();
            $table->string('validity')->nullable();
            $table->text('detail_heading_1')->nullable();
            $table->longText('detail_text_1')->nullable();
            $table->string('detail_image_1')->nullable();
            $table->integer('default_capacity')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('hero_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
        Schema::dropIfExists('courses');
    }
};
