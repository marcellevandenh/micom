<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('basket_items', function (Blueprint $table) {

            $table->foreign('event_ticket_id')
                ->references('id')
                ->on('event_tickets')
                ->nullOnDelete();
        });

    }

    public function down(): void
    {
        Schema::table('basket_items', function (Blueprint $table) {
            $table->dropForeign(['event_ticket_id']);
        });
    }
};
