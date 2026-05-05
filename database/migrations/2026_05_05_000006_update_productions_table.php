<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->foreignId('ProductID')->nullable()->after('OrderID')->constrained('products', 'ProductID');
            $table->string('ProdStatus')->default('Not Started')->after('ProductID');
        });

        Schema::table('productions', function (Blueprint $table) {
            $table->renameColumn('ProductionNote', 'ProdNote');
        });

        DB::statement('
            UPDATE productions pr
            INNER JOIN product_orders po ON po.OrderID = pr.OrderID
            SET pr.ProductID = po.ProductID
            WHERE pr.ProductID IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropForeign(['ProductID']);
            $table->dropColumn(['ProductID', 'ProdStatus']);
        });

        Schema::table('productions', function (Blueprint $table) {
            $table->renameColumn('ProdNote', 'ProductionNote');
        });
    }
};
