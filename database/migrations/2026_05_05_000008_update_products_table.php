<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'Material_ID')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('Material_ID');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'Material_ID')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('Material_ID')->nullable()->after('ProductID')->constrained('materials', 'Material_ID');
        });
    }
};
