<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id('ProductOrderID');
            $table->foreignId('OrderID')->constrained('orders', 'OrderID')->cascadeOnDelete();
            $table->foreignId('ProductID')->constrained('products', 'ProductID');
            $table->integer('Quantity');
            $table->decimal('Price', 10, 2);
            $table->timestamps();
        });

        DB::statement('
            INSERT INTO product_orders (OrderID, ProductID, Quantity, Price, created_at, updated_at)
            SELECT o.OrderID, o.ProductID, o.Quantity, p.Price, NOW(), NOW()
            FROM orders o
            INNER JOIN products p ON p.ProductID = o.ProductID
            WHERE o.ProductID IS NOT NULL AND o.Quantity IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};
