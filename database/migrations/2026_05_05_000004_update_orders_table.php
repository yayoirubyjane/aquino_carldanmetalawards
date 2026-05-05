<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('OrderStatus')->default('Pending')->after('ClientID');
            $table->date('OrderDate')->nullable()->after('OrderStatus');
            $table->date('DeliveryDate')->nullable()->after('OrderDate');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['OrderStatus', 'OrderDate', 'DeliveryDate']);
        });
    }
};
