<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->unique();
            $table->string('brand')->nullable();
            $table->string('model');
            $table->date('purchase_date')->nullable();
            $table->unsignedInteger('current_km')->default(0);
            $table->string('status')->default('available');
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->unsignedInteger('km_included_per_day')->default(0);
            $table->decimal('extra_km_price', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
