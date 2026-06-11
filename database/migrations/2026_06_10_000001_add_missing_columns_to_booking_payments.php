<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_payments', 'type')) {
                $table->string('type', 50)->after('amount');
            }
            if (!Schema::hasColumn('booking_payments', 'reference')) {
                $table->string('reference')->nullable()->after('method');
            }
            if (!Schema::hasColumn('booking_payments', 'paid_at')) {
                $table->date('paid_at')->nullable()->after('reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropColumn(['type', 'reference', 'paid_at']);
        });
    }
};
