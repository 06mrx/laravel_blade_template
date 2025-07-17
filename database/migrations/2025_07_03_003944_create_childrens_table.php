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
        Schema::create('tb_bayi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama'); // NAMA ANAK
            $table->string('nik')->unique();
            $table->date('tgl_lahir');
            $table->enum('jk', ['L', 'P']);
            $table->string('nama_ortu');
            $table->float('bb')->nullable(); // BB
            $table->float('tb')->nullable(); // TB
            $table->float('ll')->nullable(); // LL
            $table->float('lk')->nullable(); // LK
            $table->string('ket')->nullable(); // KET
            $table->uuid('created_by')->nullable();
            $table->uuid('modified_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childrens');
    }
};
