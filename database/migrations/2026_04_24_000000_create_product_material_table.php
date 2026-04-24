<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ProductID')->constrained('products', 'ProductID')->cascadeOnDelete();
            $table->foreignId('Material_ID')->constrained('materials', 'Material_ID')->cascadeOnDelete();
            $table->unsignedInteger('RequiredQuantity');
            $table->timestamps();

            $table->unique(['ProductID', 'Material_ID']);
        });

        $existingLinks = DB::table('products')
            ->whereNotNull('Material_ID')
            ->get(['ProductID', 'Material_ID']);

        foreach ($existingLinks as $link) {
            DB::table('product_material')->insert([
                'ProductID' => $link->ProductID,
                'Material_ID' => $link->Material_ID,
                'RequiredQuantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasColumn('products', 'Material_ID')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropConstrainedForeignId('Material_ID');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('products', 'Material_ID')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('Material_ID')->nullable()->after('ProductID')->constrained('materials', 'Material_ID');
            });

            $firstMaterialPerProduct = DB::table('product_material')
                ->select('ProductID', 'Material_ID')
                ->orderBy('id')
                ->get()
                ->unique('ProductID');

            foreach ($firstMaterialPerProduct as $link) {
                DB::table('products')
                    ->where('ProductID', $link->ProductID)
                    ->update(['Material_ID' => $link->Material_ID]);
            }
        }

        Schema::dropIfExists('product_material');
    }
};
