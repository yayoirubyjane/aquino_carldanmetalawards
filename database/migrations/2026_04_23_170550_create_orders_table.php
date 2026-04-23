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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID');
            $table->foreignId('EmployeeID')->nullable()->constrained('employees', 'EmployeeID');
            $table->foreignId('ProductID')->nullable()->constrained('products', 'ProductID');
            $table->foreignId('ClientID')->nullable()->constrained('clients', 'ClientID');
            $table->integer('Quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
