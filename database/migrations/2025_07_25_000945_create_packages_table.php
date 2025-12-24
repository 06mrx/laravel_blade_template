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
         Schema::create('packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade'); // tenant
            $table->string('name'); // contoh: "5Mbps Unlimited", "10Mbps 30 Hari"
            $table->string('type')->default('pppoe'); // pppoe / hotspot
            $table->string('speed_up'); // contoh: "1024k/2048k"
            $table->string('speed_down'); // contoh: "1024k/2048k"
            $table->integer('duration_days')->nullable(); // masa aktif
            $table->bigInteger('quota')->nullable(); // dalam bytes
            $table->decimal('price', 10, 2)->nullable(); // opsional
            $table->text('description')->nullable();
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
        Schema::dropIfExists('packages');
    }
};
