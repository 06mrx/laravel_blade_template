<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->foreignUuid('mikrotik_id')->nullable()->constrained('mikrotiks')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['mikrotik_id']);
            $table->dropColumn('mikrotik_id');
        });
    }
};
