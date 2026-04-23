<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id('ProductionID');
            $table->foreignId('OrderID')->nullable()->constrained('orders', 'OrderID');
            $table->text('ProductionNote')->nullable();
            $table->date('ProdStartDate')->nullable();
            $table->date('ProdFinishedDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
