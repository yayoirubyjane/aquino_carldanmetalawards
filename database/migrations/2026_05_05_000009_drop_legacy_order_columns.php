<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'ProductID') && ! Schema::hasColumn('orders', 'Quantity')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'ProductID')) {
                $table->dropForeign(['ProductID']);
                $table->dropColumn('ProductID');
            }

            if (Schema::hasColumn('orders', 'Quantity')) {
                $table->dropColumn('Quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'ProductID')) {
                $table->foreignId('ProductID')->nullable()->after('EmployeeID')->constrained('products', 'ProductID');
            }

            if (! Schema::hasColumn('orders', 'Quantity')) {
                $table->integer('Quantity')->nullable()->after('ClientID');
            }
        });
    }
};
