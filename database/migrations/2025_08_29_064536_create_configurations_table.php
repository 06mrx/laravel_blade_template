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
        Schema::create('configurations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('business_name');
            $table->string('business_logo')->nullable();
            $table->string('midtrans_client_key')->nullable();
            $table->string('midtrans_server_key')->nullable();
            $table->string('payment_type_id');
            $table->timestamps();
            $table->softDeletes(); // untuk menyimpan data yang dihapus
            $table->uuid('created_by')->nullable();
            $table->uuid('modified_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
