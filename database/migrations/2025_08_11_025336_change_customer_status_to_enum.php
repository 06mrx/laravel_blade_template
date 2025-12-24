<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus is_active lama
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        // Tambah kolom status
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('status', ['terdaftar', 'aktif', 'isolir'])
                  ->default('terdaftar')
                  ->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->boolean('is_active')->default(true)->after('name');
        });
    }
};