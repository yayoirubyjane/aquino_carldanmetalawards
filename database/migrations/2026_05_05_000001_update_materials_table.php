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
        Schema::table('materials', function (Blueprint $table) {
            // Drop the Stocks column
            $table->dropColumn('Stocks');
            // Rename Price to UnitCost
            $table->renameColumn('Price', 'UnitCost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Rename back from UnitCost to Price
            $table->renameColumn('UnitCost', 'Price');
            // Add back the Stocks column
            $table->integer('Stocks')->nullable();
        });
    }
};
