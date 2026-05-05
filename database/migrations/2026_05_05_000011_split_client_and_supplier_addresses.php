<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('ClientStreet', 120)->nullable()->after('ClientContact');
            $table->string('ClientBarangay', 120)->nullable()->after('ClientStreet');
            $table->string('ClientCity', 120)->nullable()->after('ClientBarangay');
            $table->string('ClientProvince', 120)->nullable()->after('ClientCity');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('SupplierStreet', 120)->nullable()->after('SupplierContact');
            $table->string('SupplierBarangay', 120)->nullable()->after('SupplierStreet');
            $table->string('SupplierCity', 120)->nullable()->after('SupplierBarangay');
            $table->string('SupplierProvince', 120)->nullable()->after('SupplierCity');
        });

        DB::table('clients')->orderBy('ClientID')->get()->each(function ($client) {
            $parts = array_map('trim', array_filter(explode(',', (string) ($client->ClientAddress ?? ''))));

            DB::table('clients')
                ->where('ClientID', $client->ClientID)
                ->update([
                    'ClientStreet' => $parts[0] ?? null,
                    'ClientBarangay' => $parts[1] ?? null,
                    'ClientCity' => $parts[2] ?? ($parts[1] ?? null),
                    'ClientProvince' => null,
                ]);
        });

        DB::table('suppliers')->orderBy('SupplierID')->get()->each(function ($supplier) {
            $parts = array_map('trim', array_filter(explode(',', (string) ($supplier->SupplierAddress ?? ''))));

            DB::table('suppliers')
                ->where('SupplierID', $supplier->SupplierID)
                ->update([
                    'SupplierStreet' => $parts[0] ?? null,
                    'SupplierBarangay' => $parts[1] ?? null,
                    'SupplierCity' => $parts[2] ?? ($parts[1] ?? null),
                    'SupplierProvince' => null,
                ]);
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['ClientStreet', 'ClientBarangay', 'ClientCity', 'ClientProvince']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['SupplierStreet', 'SupplierBarangay', 'SupplierCity', 'SupplierProvince']);
        });
    }
};
