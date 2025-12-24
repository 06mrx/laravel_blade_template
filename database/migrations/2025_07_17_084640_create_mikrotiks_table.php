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
         Schema::create('mikrotiks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('ip_address');
            $table->integer('port')->default(8728);
            $table->string('username');
            $table->text('password'); // akan dienkripsi
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->text('description')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->uuid('created_by')->nullable(); // siapa yang tambah
            $table->uuid('modified_by')->nullable(); // siapa yang edit
            $table->timestamps();
            $table->softDeletes();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mikrotiks');
    }
};
