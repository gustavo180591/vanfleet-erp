<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_contract_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->decimal('amount_subtotal', 10, 2)->default(0);
            $table->decimal('amount_tax', 10, 2)->default(0);
            $table->decimal('amount_total', 10, 2)->default(0);
            $table->string('status')->default('draft'); // draft, sent, paid, cancelled
            $table->string('pdf_path')->nullable();
            $table->string('verifactu_status')->default('pending'); // pending, sent, failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
