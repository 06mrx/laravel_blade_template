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
        Schema::table('mikrotiks', function (Blueprint $table) {
            $table->string('gmail')->nullable()->after('status'); // email pengirim
            $table->text('app_password_encrypted')->nullable()->after('gmail'); // app password (terenkripsi)
        });
    }

    public function down(): void
    {
        Schema::table('mikrotiks', function (Blueprint $table) {
            $table->dropColumn(['gmail', 'app_password_encrypted']);
        });
    }
};
