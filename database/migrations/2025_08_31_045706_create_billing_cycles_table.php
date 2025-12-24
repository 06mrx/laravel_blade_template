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
        Schema::create('billing_cycles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // misal: "Segment 10/20/30", "Fixed: Tanggal 5"
            $table->enum('type', ['fixed', 'segmented', 'anniversary']);

            // Untuk fixed & segmented: simpan tanggal jatuh tempo
            $table->json('due_days'); // [10], atau [5,15,25]

            // Relasi
            $table->foreignUuid('mikrotik_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained(); // siapa yang buat

            $table->boolean('is_default')->default(false); // default untuk mikrotik baru
            $table->string('created_by');
            $table->string('modified_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Index
        Schema::table('billing_cycles', function (Blueprint $table) {
            $table->index(['mikrotik_id', 'is_default']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_cycles');
    }
};
