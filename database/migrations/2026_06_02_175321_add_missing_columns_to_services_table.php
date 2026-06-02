<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('services', function (Blueprint $table) {
        $table->decimal('base_price', 12, 2)->default(0)->after('icon');
        $table->string('hero_image')->nullable()->after('icon');
        $table->string('time_mode', 50)->nullable()->after('booking_type');
        $table->unsignedInteger('free_hours')->default(0)->after('time_mode');
        $table->decimal('extra_hour_price', 12, 2)->default(0)->after('free_hours');
        $table->decimal('early_start_price', 12, 2)->default(0)->after('extra_hour_price');
        $table->decimal('late_end_price', 12, 2)->default(0)->after('early_start_price');
        $table->time('default_start_time')->nullable()->after('late_end_price');
        $table->time('default_end_time')->nullable()->after('default_start_time');
        $table->boolean('show_venue_selector')->default(false)->after('default_end_time');
        $table->boolean('show_wilaya_selector')->default(false)->after('show_venue_selector');
        $table->decimal('deposit_amount', 12, 2)->default(0)->after('show_wilaya_selector');
    });
}

public function down(): void
{
    Schema::table('services', function (Blueprint $table) {
        $table->dropColumn([
            'base_price', 'hero_image', 'time_mode', 'free_hours',
            'extra_hour_price', 'early_start_price', 'late_end_price',
            'default_start_time', 'default_end_time',
            'show_venue_selector', 'show_wilaya_selector', 'deposit_amount'
        ]);
    });
}
};