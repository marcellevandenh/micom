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
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 50)->index();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('member_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->dateTime('touch_date');
            $table->boolean('locked')->default(false);
            $table->string('status')->default('BASKET');
            $table->string('payment_reference')->nullable();
            $table->string('payment_method_key')->nullable();
            $table->dateTime('order_date')->nullable();
            $table->decimal('net_total', 10, 2)->nullable();
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('gross_total', 10, 2)->nullable();
            $table->decimal('vat_rate', 5, 4)->nullable();
            $table->string('coupon_id')->nullable();
            $table->string('xero_invoice_id')->nullable();
            $table->string('xero_invoice_number')->nullable();
            $table->string('xero_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baskets');
    }
};
