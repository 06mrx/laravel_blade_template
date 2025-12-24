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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('mikrotik_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // expired, expiring_soon
            $table->text('subject');
            $table->text('message');
            $table->boolean('success')->default(false);
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->softDeletes(); // untuk menyimpan data yang dihapus
            $table->uuid('created_by')->nullable();
            $table->uuid('modified_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
