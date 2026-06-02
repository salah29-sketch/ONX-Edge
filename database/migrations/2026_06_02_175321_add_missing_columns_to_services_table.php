<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('base_price', 10, 2)->nullable()->after('icon');
            $table->string('time_mode', 50)->nullable()->after('booking_type');
            $table->tinyInteger('show_venue_selector')->default(0)->after('time_mode');
            $table->tinyInteger('show_wilaya_selector')->default(0)->after('show_venue_selector');
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('show_wilaya_selector');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['base_price', 'time_mode', 'show_venue_selector', 'show_wilaya_selector', 'deposit_amount']);
        });
    }
};