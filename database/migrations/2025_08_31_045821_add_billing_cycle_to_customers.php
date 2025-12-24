<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignUuid('billing_cycle_id')->nullable()->constrained('billing_cycles')->nullOnDelete();
            $table->date('registration_date')->default(now()); // untuk anniversary
            $table->date('next_invoice_date')->nullable(); // cache: kapan tagihan berikutnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['billing_cycle_id', 'registration_date', 'next_invoice_date']);
        });
    }
};
