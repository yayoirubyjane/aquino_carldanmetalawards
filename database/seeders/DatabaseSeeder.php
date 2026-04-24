<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            ['ClientFN' => 'Juan', 'ClientMN' => 'D.', 'ClientLN' => 'Dela Cruz', 'ClientContact' => '09123456789', 'ClientAddress' => 'Matina, Davao City', 'created_at' => now(), 'updated_at' => now()],
            ['ClientFN' => 'Maria', 'ClientMN' => 'S.', 'ClientLN' => 'Santos', 'ClientContact' => '09987654321', 'ClientAddress' => 'Buhangin, Davao City', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('employees')->insert([
            ['EmployeeFN' => 'Dexter', 'EmployeeMN' => 'B.', 'EmployeeLN' => 'Aquino', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('materials')->insert([
            ['MaterialName' => 'Premium Acrylic Sheet', 'MaterialType' => 'Acrylic', 'Stocks' => 50, 'Price' => 500.00, 'created_at' => now(), 'updated_at' => now()],
            ['MaterialName' => 'Mahogany Wood Base', 'MaterialType' => 'Wood', 'Stocks' => 20, 'Price' => 350.00, 'created_at' => now(), 'updated_at' => now()],
            ['MaterialName' => 'Metal Plate Insert', 'MaterialType' => 'Metal', 'Stocks' => 30, 'Price' => 150.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('products')->insert([
            ['ProductName' => 'Academic Excellence Plaque', 'ProductType' => 'Plaque', 'Price' => 1200.00, 'created_at' => now(), 'updated_at' => now()],
            ['ProductName' => 'Sports Championship Trophy', 'ProductType' => 'Trophy', 'Price' => 2500.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('product_material')->insert([
            ['ProductID' => 1, 'Material_ID' => 1, 'RequiredQuantity' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ProductID' => 1, 'Material_ID' => 2, 'RequiredQuantity' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ProductID' => 1, 'Material_ID' => 3, 'RequiredQuantity' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ProductID' => 2, 'Material_ID' => 1, 'RequiredQuantity' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['ProductID' => 2, 'Material_ID' => 2, 'RequiredQuantity' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('orders')->insert([
            ['EmployeeID' => 1, 'ProductID' => 1, 'ClientID' => 1, 'Quantity' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['EmployeeID' => 1, 'ProductID' => 2, 'ClientID' => 2, 'Quantity' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('productions')->insert([
            ['OrderID' => 1, 'ProductionNote' => 'Prototype approved. Starting full production run.', 'ProdStartDate' => Carbon::now()->subDays(3), 'ProdFinishedDate' => null, 'created_at' => now(), 'updated_at' => now()],
            ['OrderID' => 2, 'ProductionNote' => 'Awaiting raw materials delivery for the wood bases.', 'ProdStartDate' => Carbon::now(), 'ProdFinishedDate' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
