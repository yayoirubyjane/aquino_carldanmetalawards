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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('StockID');
            $table->foreignId('SupplierID')->nullable()->constrained('suppliers', 'SupplierID');
            $table->foreignId('Material_ID')->nullable()->constrained('materials', 'Material_ID');
            $table->integer('StockIN')->default(0);
            $table->integer('StockOUT')->default(0);
            $table->integer('Quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
