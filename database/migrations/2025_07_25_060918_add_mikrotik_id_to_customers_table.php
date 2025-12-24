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
        Schema::table('customers', function (Blueprint $table) {
            // Tambah kolom mikrotik_id
            $table->foreignUuid('mikrotik_id')->nullable()->constrained('mikrotiks')->nullOnDelete();

            // Opsional: jika ingin pastikan uniqueness per MikroTik
            $table->unique(['username', 'mikrotik_id'], 'unique_username_per_mikrotik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('unique_username_per_mikrotik');
            $table->dropForeign(['mikrotik_id']);
            $table->dropColumn('mikrotik_id');
        });
    }
};
