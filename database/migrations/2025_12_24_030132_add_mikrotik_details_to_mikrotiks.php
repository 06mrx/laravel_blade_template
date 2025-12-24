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
            // $table->foreignUuid('billing_cycle_id')->nullable()->constrained('billing_cycles')->nullOnDelete();
            $table->string('sn')->nullable();
            $table->string('odc')->nullable();
            $table->string('odp')->nullable();
            $table->integer('port')->nullable();
            $table->string('maps_url')->nullable();
            $table->date('installation_date')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['sn', 'odc', 'odp', 'port', 'installation_date', 'maps_url']);
        });
    }
};
