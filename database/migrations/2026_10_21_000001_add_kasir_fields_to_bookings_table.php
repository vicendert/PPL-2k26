<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id')
                  ->comment('Nama pelanggan walk-in (diisi oleh kasir, null jika booking online)');
            $table->text('notes')->nullable()->after('payment_proof')
                  ->comment('Catatan tambahan dari kasir');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'notes']);
        });
    }
};
