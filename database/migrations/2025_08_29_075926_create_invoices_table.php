<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number')->unique();
            $table->foreignUuid('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignUuid('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->decimal('amount', 12, 0); // IDR, tanpa desimal
            $table->date('issue_date');
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'paid', 'overdue', 'canceled'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('modified_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tambahkan index untuk performa
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['customer_id', 'status']);
            $table->index('issue_date');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};