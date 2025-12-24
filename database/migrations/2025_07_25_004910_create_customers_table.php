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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade'); // tenant
            $table->string('name'); // nama pelanggan
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('username');
            $table->string('password'); // password asli (akan dienkripsi di RADIUS)
            $table->foreignUuid('package_id')->nullable()->constrained('packages')->onDelete('set null');
            $table->foreignUuid('ip_pool_id')->nullable()->constrained('ip_pools')->onDelete('set null');
            $table->timestamp('expired_at')->nullable(); // masa aktif
            $table->boolean('is_active')->default(true);
            $table->string('id_number', 16)->after('name')->nullable(); // 16 digit KTP
            $table->unique(['id_number', 'mikrotik_id']); // composite unique
            $table->uuid('created_by')->nullable();
            $table->uuid('modified_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
