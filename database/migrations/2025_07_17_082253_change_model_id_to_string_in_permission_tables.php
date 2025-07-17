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
         // Ubah kolom model_id di model_has_roles jadi string(36)
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_id', 36)->change();
        });

        // Ubah kolom model_id di model_has_permissions jadi string(36)
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->string('model_id', 36)->change();
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->string('auditable_id', 36)->change();
            $table->string('user_id', 36)->nullable()->change();
        });

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->unsignedBigInteger('auditable_id')->change();
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });


    }
};
